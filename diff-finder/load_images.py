from ImageCompare import ImageCompare
import os 
import time
import requests
import mysql.connector

back_end_enpoint = 'http://laravel.test'

def init_db():
    mydb = mysql.connector.connect(
        host="mysql",
        user="sail",
        password="password",
        database="diff-mania"
      )
    return mydb

mydb = init_db()

def download_image(url: str, save_path: str) -> bool:
    response = requests.get(url)
    directory = save_path.split("/")
    directory = "/".join(directory[:-1])
    if response.status_code == 200:
      if not os.path.exists(directory):
        os.makedirs(directory)
      with open(save_path, 'wb') as f:
          f.write(response.content)
    else:
        print(f"Failed to download image from {url}")
        return False
    return True
  
def check_if_image_exists_on_server(image_path: str) -> bool:
    response = requests.get(image_path)
    return response.status_code == 200

def main():
  # Download the images
  response = requests.get(f'{back_end_enpoint}/api/images/count')
  if response.status_code == 200:
    image_count = response.json().get('count', 0)
  else:
    print("Failed to retrieve image count from server")
    return
  
  # Check what is the last image in database and start from there
  mycursor = mydb.cursor()
  mycursor.execute("SELECT * FROM images")
  results = mycursor.fetchall()
  
  # Change the 0-th index of results array to integer and sort the array desceding then get the first element
  
  last_image_id = 0

  if len(results) > 0:
    result = sorted(results, key=lambda x: int(x[0]), reverse=True)[0]
    last_image_id = result[0]
    last_image_id = int(last_image_id)
  else:
    last_image_id = 1
    
  if last_image_id == image_count:
    print("All images are already in the database.")
    return

  for i in range(last_image_id + 1, image_count + 1):
    # Check if one file exists and if not try .jpg extension. If it does not exist at all jump to the next iteration
    image_ending = "png"
    if not check_if_image_exists_on_server(f"{back_end_enpoint}/images/{i}/1.png"):
      image_ending = "jpg"
      if not check_if_image_exists_on_server(f"{back_end_enpoint}/images/{i}/1.jpg"):
        continue
      
    url1 = f"{back_end_enpoint}/images/{i}/1.{image_ending}"
    url2 = f"{back_end_enpoint}/images/{i}/2.{image_ending}"
    save_path1 = f"in/{i}/1.jpg"
    save_path2 = f"in/{i}/2.jpg"
    download_image(url1, save_path1)
    download_image(url2, save_path2)
  
  # Load all paths to images recursively from the in folder
  images = []
  for root, dirs, files in os.walk('in'):
    for file in files:
      if file.endswith('.png') or file.endswith('.jpg'):
        images.append(os.path.join(root, file))
        
  # Remove all images which have the name 3.png or 3.jpg or are in Original folder
  images = [image for image in images if '3.png' not in image and '3.jpg' not in image and 'Original' not in image]
  
  # Loop Thorugh image pairs and compare them with the ImageCompare class
  comparer = ImageCompare()
  
  for i in range(last_image_id + 1, len(images), 2):
    image1_path = images[i + 1]
    image2_path = images[i]
    
    print(f"Comparing {image1_path} and {image2_path}")
    
    differences = comparer.find_image_differences(image1_path, image2_path)
    difficulty = 1
    
    if(len(differences) <= 5):
      difficulty = 1
    elif(len(differences) <= 7):
      difficulty = 2
    else:
      difficulty = 3
        
    # Get the image id from the image path
    image_id = image1_path.split("/")[1]
    
    json = comparer.return_differences_as_json()
    
    mycursor = mydb.cursor()
    sql = "INSERT INTO images (name, path, differences, json_diff, difficulty, created_at, updated_at) VALUES (%s, %s, %s, %s, %s, %s, %s)"
    val = (image_id, f'images/{image_id}', len(differences), json, difficulty, time.strftime('%Y-%m-%d %H:%M:%S'), time.strftime('%Y-%m-%d %H:%M:%S'))
    mycursor.execute(sql, val)
    mydb.commit()

    # remove the images after they have been compared
    os.remove(image1_path)
    os.remove(image2_path)
    
    
  
if __name__ == "__main__":
    t1 = time.time()
    main()
    t2 = time.time()

    print(f"Execution time: {t2 - t1} seconds")