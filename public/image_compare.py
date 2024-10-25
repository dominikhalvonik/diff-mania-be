import cv2
import numpy as np
import requests
import os

def download_image(url, save_path):
    response = requests.get(url)
    if response.status_code == 200:
        with open(save_path, 'wb') as f:
            f.write(response.content)
    else:
        print(f"Failed to download image from {url}")
        return False
    return True

def compare_images(url1, url2):
    # Stiahnutie obrázkov
    if not download_image(url1, "image1.png") or not download_image(url2, "image2.png"):
        return []

    # Načítanie obrázkov
    image1 = cv2.imread("image1.png", cv2.IMREAD_GRAYSCALE)
    image2 = cv2.imread("image2.png", cv2.IMREAD_GRAYSCALE)

    # Skontrolovanie, či obrázky majú rovnaké rozmery
    if image1.shape != image2.shape:
        print("Obrázky nemajú rovnaké rozmery a nemôžu byť porovnané.")
        return []

    # Výpočet rozdielov
    diff = cv2.absdiff(image1, image2)
    _, thresh = cv2.threshold(diff, 30, 255, cv2.THRESH_BINARY)

    # Získanie kontúr rozdielov
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    # Výpočet stredných bodov rozdielov
    differences = []
    for contour in contours:
        if cv2.contourArea(contour) > 50:  # Ignorovať malé oblasti
            M = cv2.moments(contour)
            if M["m00"] != 0:
                cX = int(M["m10"] / M["m00"])
                cY = int(M["m01"] / M["m00"])
                differences.append((cX, cY))

    # Odstránenie dočasných súborov
    os.remove("image1.png")
    os.remove("image2.png")

    return differences

if __name__ == "__main__":
    url1 = 'https://appspowerplaymanager.vshcdn.net/image1.png'
    url2 = 'https://appspowerplaymanager.vshcdn.net/image2.png'
    differences = compare_images(url1, url2)
    print(f"Počet rozdielov: {len(differences)}")
    for diff in differences:
        print(f"Rozdiel na pozícii X: {diff[0]}, Y: {diff[1]}")
