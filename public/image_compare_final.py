import cv2
import numpy as np
import json

def merge_bounding_boxes(bboxes, merge_threshold=5):
    # List to hold merged bounding boxes
    merged_bboxes = []

    # Sort bounding boxes by their 'x' coordinate (access the 'x' value of each dictionary)
    bboxes = sorted(bboxes, key=lambda x: x["x"])

    while bboxes:
        # Take the first bounding box
        current = bboxes.pop(0)

        # Merge boxes that are close to each other or overlap
        merged = False
        for i, other in enumerate(merged_bboxes):
            # Check if the current bbox intersects with any of the existing merged boxes
            if (current["x"] < other["x"] + other["width"] + merge_threshold and
                current["x"] + current["width"] + merge_threshold > other["x"] and
                current["y"] < other["y"] + other["height"] + merge_threshold and
                current["y"] + current["height"] + merge_threshold > other["y"]):
                # Merge the bounding boxes: take the min x, min y, max width, and max height
                new_bbox = {
                    "x": min(current["x"], other["x"]),
                    "y": min(current["y"], other["y"]),
                    "width": max(current["x"] + current["width"], other["x"] + other["width"]) - min(current["x"], other["x"]),
                    "height": max(current["y"] + current["height"], other["y"] + other["height"]) - min(current["y"], other["y"])
                }
                # Update the merged bounding box
                merged_bboxes[i] = new_bbox
                merged = True
                break
        
        # If no merging happened, add the current bounding box to the list
        if not merged:
            merged_bboxes.append(current)

    return merged_bboxes

def find_image_differences(image1_path, image2_path):
    # Load the two images
    img1 = cv2.imread(image1_path)
    img2 = cv2.imread(image2_path)

    # Convert to grayscale for easier comparison
    gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
    gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)

    # Compute the absolute difference between the images
    diff = cv2.absdiff(gray1, gray2)
    
    # Lower threshold value to capture smaller differences
    _, thresh = cv2.threshold(diff, 25, 255, cv2.THRESH_BINARY)  # Lowering the threshold value from 50 to 25

    # Find contours of the differences
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    # Prepare a list to store differences as coordinates
    bboxes = []

    for contour in contours:
        # Include smaller differences, with a lower minimum area threshold
        if cv2.contourArea(contour) > 20:  # Lowering the contour area threshold from 100 to 20
            # Get bounding box around the difference
            x, y, w, h = cv2.boundingRect(contour)

            # Add padding around the bounding box for easier selection (20 pixels)
            padding = 15
            x -= padding
            y -= padding
            w += 2 * padding
            h += 2 * padding

            # Ensure the bounding box stays within the image boundaries
            x = max(x, 0)
            y = max(y, 0)
            h = min(h, img1.shape[0] - y)
            w = min(w, img1.shape[1] - x)

            bboxes.append({"x": x, "y": y, "width": w, "height": h})

    # Merge overlapping or nearby bounding boxes
    merged_bboxes = merge_bounding_boxes(bboxes)

    return merged_bboxes

def save_differences_to_json(image_id, differences):
    # Structure the data to be saved in JSON format
    data = {
        "image_id": image_id,
        "differences": differences
    }

    # Save the JSON data to a file
    with open(f"{image_id}_differences.json", "w") as json_file:
        json.dump(data, json_file, indent=4)

def create_sections_from_differences(image1_path, json_path, output_image_path):
    # Load the base image
    img = cv2.imread(image1_path)

    # Load the differences from the JSON file
    with open(json_path, 'r') as json_file:
        data = json.load(json_file)

    # Extract the differences list from the JSON data
    differences = data.get("differences", [])

    # Iterate over each difference and draw a rectangle on the base image
    for diff in differences:
        # Ensure we're using the correct keys for the dictionary
        x = diff["x"]
        y = diff["y"]
        w = diff["width"]
        h = diff["height"]

        # Draw a red rectangle around each difference
        cv2.rectangle(img, (x, y), (x + w, y + h), (0, 0, 255), 2)  # Red color (BGR format)

    # Save the new image with sections highlighted
    cv2.imwrite(output_image_path, img)

    print(f"Image with sections saved as {output_image_path}")

def main():
    # Example image paths
    image1_path = '1.png'
    image2_path = '2.png'
    
    # Compare the two images and get merged differences
    merged_bboxes = find_image_differences(image1_path, image2_path)

    # Save the merged differences in JSON format
    image_id = "image_pair_1"
    save_differences_to_json(image_id, merged_bboxes)

    print(f"Differences saved for {image_id}!")

    json_path = 'image_pair_1_differences.json'  # Path to the JSON containing the differences
    output_image_path = 'image_with_sections.png'  # Output image with highlighted sections

    # Create sections (highlight differences) on the base image
    create_sections_from_differences(image1_path, json_path, output_image_path)

if __name__ == "__main__":
    main()
