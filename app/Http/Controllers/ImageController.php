<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController
{
    public function compareImages(Request $request): JsonResponse
    {
        // Načítanie obsahu obrázkov z URL
        $imageData1 = file_get_contents($request->image1);
        $imageData2 = file_get_contents($request->image2);

        // Vytvorenie obrázkových zdrojov z obsahu
        $image1 = imagecreatefromstring($imageData1);
        $image2 = imagecreatefromstring($imageData2);

        // Získanie šírky a výšky obrázkov
        $width1 = imagesx($image1);
        $height1 = imagesy($image1);
        $width2 = imagesx($image2);
        $height2 = imagesy($image2);

        // Skontrolovanie, či obrázky majú rovnaké rozmery
        if ($width1 !== $width2 || $height1 !== $height2) {
            imagedestroy($image1);
            imagedestroy($image2);

            return response()->json([
                'success' => false,
                'message' => 'Obrázky nemajú rovnaké rozmery a nemôžu byť porovnané.',
            ]);
        }

        // Pole na ukladanie rozdielov
        $differences = [];

        // Iterácia cez každý pixel
        for ($x = 0; $x < $width1; $x++) {
            for ($y = 0; $y < $height1; $y++) {
                // Porovnanie pixelov na tých istých súradniciach v obidvoch obrázkoch
                if (imagecolorat($image1, $x, $y) !== imagecolorat($image2, $x, $y)) {
                    // Ak sa pixel líši, pridáme jeho súradnice do pola
                    $differences[] = ['x' => $x, 'y' => $y];
                }
            }
        }

        // Uvoľnenie pamäte
        imagedestroy($image1);
        imagedestroy($image2);

        $diffGroups = $this->groupDifferences($differences);

        return response()->json([
            'success' => true,
            'message' => 'Images are compared successfully',
            'differences' => $diffGroups
        ]);
    }

    public function groupDifferences(array $differences, int $threshold = 5): array
    {
        if (empty($differences)) {
            return [];
        }

        // Zoradiť rozdiely podľa X a potom podľa Y
        usort($differences, function($a, $b) {
            return ($a['x'] <=> $b['x']) ?: ($a['y'] <=> $b['y']);
        });

        $groupedDifferences = [];
        $currentGroup = [];
        $lastPixel = null;

        foreach ($differences as $pixel) {
            if ($lastPixel === null || (abs($pixel['x'] - $lastPixel['x']) <= $threshold && abs($pixel['y'] - $lastPixel['y']) <= $threshold)) {
                $currentGroup[] = $pixel;
            } else {
                $groupedDifferences[] = $this->calculateGroupCenter($currentGroup);
                $currentGroup = [$pixel];
            }
            $lastPixel = $pixel;
        }

        // Pridanie poslednej skupiny
        if (!empty($currentGroup)) {
            $groupedDifferences[] = $this->calculateGroupCenter($currentGroup);
        }

        return $groupedDifferences;
    }

    public function calculateGroupCenter($group): array
    {
        $count = count($group);
        if ($count % 2 == 1) {
            // Ak je počet prvkov nepárny, vrátime stredný prvok
            return $group[floor($count / 2)];
        } else {
            // Ak je počet prvkov párny, vrátime ten vzdialenejší od osi X
            $middle1 = $group[$count / 2 - 1];
            $middle2 = $group[$count / 2];
            return ($middle1['x'] >= $middle2['x']) ? $middle1 : $middle2;
        }
    }
}
