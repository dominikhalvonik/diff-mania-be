import cv2
import numpy as np
import json
from skimage.metrics import structural_similarity as ssim
import requests
import os



def download_image(url: str, save_path: str) -> bool:
    response = requests.get(url)
    if response.status_code == 200:
        with open(save_path, 'wb') as f:
            f.write(response.content)
    else:
        print(f"Failed to download image from {url}")
        return False
    return True


# def find_differences_ssim(gray1, gray2):
#     # Compute the Structural Similarity Index (SSIM) between the images
#     score, diff = ssim(gray1, gray2, full=True)
#     diff = (diff * 255).astype("uint8")
#     _, thresh = cv2.threshold(diff, 30, 255, cv2.THRESH_BINARY)
    
#     # Find contours of the differences
#     contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    
#     # Mark differences and calculate centers
#     centers = []
#     for contour in contours:
#         if cv2.contourArea(contour) > 20:  # Filter small contours
#             (x, y, w, h) = cv2.boundingRect(contour)
#             center_x, center_y = x + w // 2, y + h // 2
#             centers.append({"x": center_x, "y": center_y})
#             cv2.circle(image2, (center_x, center_y), 10, (0, 255, 0), 3)
    
#     # Save results
#     cv2.imwrite('differences_ssim.png', image2)
#     with open('differences_ssim_centers.json', 'w') as f:
#         json.dump(centers, f)
#     print("SSIM version saved results.")



def find_refined_differences(gray1, gray2, original_image, max_iterations=100):
    # Compute the SSIM between the two images
    score, diff = ssim(gray1, gray2, full=True)
    diff = (diff * 255).astype("uint8")

    # Initial parameters
    threshold_value = 30
    min_area = 50
    max_area = 500
    iteration = 0
    centers = []

    # Try to refine detection until we find exactly 7 differences or reach max iterations
    while iteration < max_iterations:
        # Threshold the diff image and find contours
        _, thresh = cv2.threshold(diff, threshold_value, 255, cv2.THRESH_BINARY)
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        filtered_contours = []

        # Filter contours based on area
        for contour in contours:
            area = cv2.contourArea(contour)
            x, y, w, h = cv2.boundingRect(contour)
            aspect_ratio = w / float(h)
            
            # Filter contours by area and aspect ratio to refine detection
            if min_area < area < max_area and 0.5 < aspect_ratio < 2.0:
                filtered_contours.append(contour)

        # If we have exactly 7 contours, break out of the loop
        if len(filtered_contours) == 7:
            centers = [{"x": x + w // 2, "y": y + h // 2} for (x, y, w, h) in 
                       [cv2.boundingRect(c) for c in filtered_contours]]
            for center in centers:
                cv2.circle(original_image, (center["x"], center["y"]), 10, (0, 0, 255), 3)
            print(f"Found 7 differences in {iteration + 1} iterations.")
            break

        # Adjust parameters for next iteration
        threshold_value = min(threshold_value + 5, 100)  # Increase threshold, max 100
        min_area = min(min_area + 10, 100)               # Increase min_area, max 100
        max_area = min(max_area + 50, 1000)              # Increase max_area, max 1000
        iteration += 1

    # If not exactly 7 differences, still mark detected differences closest to 7
    if len(filtered_contours) != 7:
        print(f"Could not find exactly 7 differences. Found {len(filtered_contours)} differences after {max_iterations} iterations.")
        filtered_contours = filtered_contours[:7]  # Limit to 7 if there are more

        centers = [{"x": x + w // 2, "y": y + h // 2} for (x, y, w, h) in 
                   [cv2.boundingRect(c) for c in filtered_contours]]
        for center in centers:
            cv2.circle(original_image, (center["x"], center["y"]), 10, (0, 0, 255), 3)

    # Save the output image and JSON with center points
    cv2.imwrite('refined_differences.png', original_image)
    with open('refined_differences_centers.json', 'w') as f:
        json.dump(centers, f)
    print("Refined version saved results.")



if __name__ == "__main__":
    url1 = 'https://appspowerplaymanager.vshcdn.net/image1.png'
    url2 = 'https://appspowerplaymanager.vshcdn.net/image2.png'

      # Check if the images are loaded already
    if not os.path.isfile("image1.png") or not os.path.isfile("image2.png"):
      # Download images
      print('Downloading images...')
      if not download_image(url1, "image1.png") or not download_image(url2, "image2.png"):
          exit()
    else:
        print('Images already downloaded')


    # Načítanie obrázkov
    image1 = cv2.imread("image1.png")
    image2 = cv2.imread("image2.png")

    # Skontrolovanie, či obrázky majú rovnaké rozmery
    if image1.shape != image2.shape:
        print("Obrázky nemajú rovnaké rozmery a nemôžu byť porovnané.")
        exit()

    # Convert to grayscale
    gray1 = cv2.cvtColor(image1, cv2.COLOR_BGR2GRAY)
    gray2 = cv2.cvtColor(image2, cv2.COLOR_BGR2GRAY)

    find_refined_differences(gray1, gray2, image1)

    os.remove("image1.png")
    os.remove("image2.png")
    # find_differences_ssim(gray1, gray2)
