from ImageCompare import ImageCompare
import os 
import time
import requests
import mysql.connector

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

def main():
  # Download the images
  download_image("http://laravel.test/images/1/1.png", "in/1/1.jpg")
  download_image("http://laravel.test/images/1/2.png", "in/1/2.jpg")
  
  # Load all paths to images recursively from the in folder
  images = []
  for root, dirs, files in os.walk('in'):
    for file in files:
      if file.endswith('.png') or file.endswith('.jpg'):
        images.append(os.path.join(root, file))
        
  # Remove all images which have the name 3.png or 3.jpg or are in Original folder
  images = [image for image in images if '3.png' not in image and '3.jpg' not in image and 'Original' not in image]
  
  print(images)  
  
  # Loop Thorugh image pairs and compare them with the ImageCompare class
  comparer = ImageCompare()
  
  for i in range(0, len(images), 2):
    image1_path = images[i + 1]
    image2_path = images[i]
    
    print(f"Comparing {image1_path} and {image2_path}")
    
    differences = comparer.find_image_differences(image1_path, image2_path)
    
    print(f"Found {len(differences)} differences between {image1_path} and {image2_path}")
    
    # Get the image id from the image path
    image_id = image1_path.split("/")[1]
    
    json = comparer.return_differences_as_json()
    
    mycursor = mydb.cursor()
    
    # Check if the image_id already exists in the database
    check_sql = "SELECT COUNT(*) FROM images WHERE path = %s"
    check_val = (f'images/{image_id}',)
    mycursor.execute(check_sql, check_val)
    result = mycursor.fetchone()
    
    if result[0] == 0:
      # Use the already created table images in the database. Save the image path in public folder amount of differences and as a string the json_diff
      sql = "INSERT INTO images (path, differences, json_diff, created_at, updated_at) VALUES (%s, %s, %s, %s, %s)"
      val = (f'images/{image_id}', len(differences), json, time.strftime('%Y-%m-%d %H:%M:%S'), time.strftime('%Y-%m-%d %H:%M:%S'))
      mycursor.execute(sql, val)
      mydb.commit()
    else:
      print(f"Image with id {image_id} already exists in the database.")
    
  
if __name__ == "__main__":
    t1 = time.time()
    main()
    t2 = time.time()

    print(f"Execution time: {t2 - t1} seconds")