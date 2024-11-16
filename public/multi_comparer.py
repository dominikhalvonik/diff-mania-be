from ImageCompare import ImageCompare
import os 
import time

def main():
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
    print(image_id)
    
    # Save them to json in the out directory
    comparer.save_differences_to_json("difference", f"out/{image_id}")
    
    # # Show the differences on an image
    comparer.create_image_with_differences(image1_path, f"out/{image_id}/differences.png")
  
  
if __name__ == "__main__":
    t1 = time.time()
    main()
    t2 = time.time()

    print(f"Execution time: {t2 - t1} seconds")