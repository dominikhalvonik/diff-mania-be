import cv2
import json
import os
from typing import List, Dict

class ImageCompare:
  def __init__(self, merge_threshold: int = 5, contour_area_threshold: int = 20, padding: int = 15):
    """
    Initialize the ImageCompare class with default parameters.

    :param merge_threshold: Threshold for merging bounding boxes. Default is 5.
    :param contour_area_threshold: Minimum area of contours to be considered as differences. Default is 20.
    :param padding: Padding around bounding boxes. Default is 15.
    """
    self.merge_threshold = merge_threshold
    self.contour_area_threshold = contour_area_threshold
    self.padding = padding

  def merge_bounding_boxes(self, bboxes: List[Dict[str, int]]) -> List[Dict[str, int]]:
    """
    Merge overlapping or nearby bounding boxes.

    :param bboxes: List of bounding boxes.
    :return: List of merged bounding boxes.
    """
    merged_bboxes = []
    bboxes = sorted(bboxes, key=lambda x: x["x"])

    while bboxes:
      current = bboxes.pop(0)
      merged = False
      for i, other in enumerate(merged_bboxes):
        if (current["x"] < other["x"] + other["width"] + self.merge_threshold and
          current["x"] + current["width"] + self.merge_threshold > other["x"] and
          current["y"] < other["y"] + other["height"] + self.merge_threshold and
          current["y"] + current["height"] + self.merge_threshold > other["y"]):
          new_bbox = {
            "x": min(current["x"], other["x"]),
            "y": min(current["y"], other["y"]),
            "width": max(current["x"] + current["width"], other["x"] + other["width"]) - min(current["x"], other["x"]),
            "height": max(current["y"] + current["height"], other["y"] + other["height"]) - min(current["y"], other["y"])
          }
          merged_bboxes[i] = new_bbox
          merged = True
          break
      if not merged:
        merged_bboxes.append(current)

    return merged_bboxes

  def find_image_differences(self, image1_path: str, image2_path: str) -> List[Dict[str, int]]:
    """
    Find differences between two images.

    :param image1_path: Path to the first image.
    :param image2_path: Path to the second image.
    :return: List of bounding boxes representing differences.
    """
    img1 = cv2.imread(image1_path)
    img2 = cv2.imread(image2_path)
    if img1.shape != img2.shape:
      print("Images do not have the same dimensions.")
      return []
      
    gray1 = cv2.cvtColor(img1, cv2.COLOR_BGR2GRAY)
    gray2 = cv2.cvtColor(img2, cv2.COLOR_BGR2GRAY)
    diff = cv2.absdiff(gray1, gray2)
    _, thresh = cv2.threshold(diff, 25, 255, cv2.THRESH_BINARY)
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
    bboxes = []

    for contour in contours:
      if cv2.contourArea(contour) > self.contour_area_threshold:
        x, y, w, h = cv2.boundingRect(contour)
        x -= self.padding
        y -= self.padding
        w += 2 * self.padding
        h += 2 * self.padding
        x = max(x, 0)
        y = max(y, 0)
        h = min(h, img1.shape[0] - y)
        w = min(w, img1.shape[1] - x)
        bboxes.append({"x": x, "y": y, "width": w, "height": h})
        
    self.differences = self.merge_bounding_boxes(bboxes)

    return self.differences
  
  def create_out_path(self, out_path):
    # Create the out directory if it does not exist
    if not os.path.exists(out_path):
      os.makedirs(out_path)
      

  def save_differences_to_json(self, image_id: str, out_path: str = "out") -> None:
    """
    Save differences to a JSON file.

    :param image_id: Identifier for the image.
    :param out_path: Path to save the JSON file. Default is "out".
    """
    if not hasattr(self, 'differences'):
      raise ValueError("Differences have not been calculated. Please run find_image_differences first.")
    
    data = {
      "image_id": image_id,
      "differences": self.differences
    }
    
    # Create the out directory if it does not exist
    self.create_out_path(out_path)
  
    with open(f"{out_path}/{image_id}.json", "w") as f:
      json.dump(data, f)
      
  def return_differences_as_json(self) -> str:
    """
    Return differences as a JSON string.

    :return: JSON string of differences.
    """
    if not hasattr(self, 'differences'):
      raise ValueError("Differences have not been calculated. Please run find_image_differences first.")
    
    data = {
      "differences": self.differences
    }
    
    return json.dumps(data)


  def create_image_with_differences(self, image1_path: str, output_image_path: str) -> None:
    """
    Create sections from differences and save the output image.

    :param image1_path: Path to the base image.
    :param json_path: Path to the JSON file containing differences.
    :param output_image_path: Path to save the output image.
    """
    if not hasattr(self, 'differences'):
      raise ValueError("Differences have not been calculated. Please run find_image_differences first.")
    
    img = cv2.imread(image1_path)

    for diff in self.differences:
      x = diff["x"]
      y = diff["y"]
      w = diff["width"]
      h = diff["height"]
      cv2.rectangle(img, (x, y), (x + w, y + h), (0, 0, 255), 2)
      
    cv2.imwrite(output_image_path, img)
    print(f"Image with sections saved as {output_image_path}")

  def save_original_images(self, image1_path: str, image2_path: str, output_image_path: str) -> None:
    """
    Save the original images to the output folder.
    """
    if not hasattr(self, 'differences'):
      raise ValueError("Differences have not been calculated. Please run find_image_differences first.")
    
    self.create_out_path(output_image_path)
    
    img1 = cv2.imread(image1_path)
    img2 = cv2.imread(image2_path)
    
    cv2.imwrite(f"{output_image_path}/image1.png", img1)
    cv2.imwrite(f"{output_image_path}/image2.png", img2)
    print(f"Original images saved as {output_image_path}/image1.png and {output_image_path}/image2.png")