from ImageCompare import ImageCompare
import time

def main():
    # Example image paths
    image1_path = '1.png'
    image2_path = '2.png'
    
    # Utlize the ImageCompare class 
    comparer = ImageCompare()
    differences = comparer.find_image_differences(image1_path, image2_path)
    
    print(differences)
    
    # Save them to json
    comparer.save_differences_to_json("image_1")
    
    # Show the differences on an image
    comparer.create_image_with_differences(image1_path, 'out.png')
    




if __name__ == "__main__":
    t1 = time.time()
    main()
    t2 = time.time()

    print(f"Execution time: {t2 - t1} seconds")
