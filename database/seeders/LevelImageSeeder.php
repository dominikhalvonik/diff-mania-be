<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\LevelImage;
use Illuminate\Database\Seeder;
use App\Models\Image;



class LevelImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $episodeConfig = [
            1 => ['easy' => 15, 'medium' => 3, 'hard' => 2],
            2 => ['easy' => 13, 'medium' => 4, 'hard' => 3],
            3 => ['easy' => 11, 'medium' => 5, 'hard' => 4],
            4 => ['easy' => 9, 'medium' => 6, 'hard' => 5],
            5 => ['easy' => 7, 'medium' => 7, 'hard' => 6],
            6 => ['easy' => 5, 'medium' => 8, 'hard' => 7],
            7 => ['easy' => 3, 'medium' => 9, 'hard' => 8],
            8 => ['easy' => 0, 'medium' => 10, 'hard' => 10],
            9 => ['easy' => 0, 'medium' => 9, 'hard' => 11],
            10 => ['easy' => 0, 'medium' => 8, 'hard' => 12],
        ];

        $levelPictureAmount = [
            1 => 1,
            2 => 1,
            3 => 1,
            4 => 1,
            5 => 1,
            6 => 2,
            7 => 1,
            8 => 1,
            9 => 1,
            10 => 3,
            11 => 1,
            12 => 2,
            13 => 1,
            14 => 1,
            15 => 1,
            16 => 2,
            17 => 1,
            18 => 1,
            19 => 1,
            20 => 3,
        ];

        $episodes = Episode::all();
        $images = Image::all();

        $episodes->load('levels');

        foreach ($episodes as $episode) {
            $levels = $episode->levels;
            $amountOfEasyPictures = $episodeConfig[$episode->id]['easy'];
            $amountOfMediumPictures = $episodeConfig[$episode->id]['medium'];
            $amountOfHardPictures = $episodeConfig[$episode->id]['hard'];

            for ($i = 1; $i <= $amountOfEasyPictures; $i++) {
                $pictureAmount = $levelPictureAmount[$i];
                for ($j = 1; $j <= $pictureAmount; $j++) {
                    $randomEasyImage = $images->where('difficulty', 1)->random()->name;
                    LevelImage::create([
                        'level_id' => $levels->where('name', $i)->first()->id,
                        'image_name' => $randomEasyImage,
                    ]);
                }
            }

            for ($i = $amountOfEasyPictures + 1; $i <= $amountOfMediumPictures; $i++) {
                $pictureAmount = $levelPictureAmount[$i];
                for ($j = 1; $j <= $pictureAmount; $j++) {
                    $randomMediumImage = $images->where('difficulty', 2)->random()->name;
                    LevelImage::create([
                        'level_id' => $levels->where('name', $i)->first()->id,
                        'image_name' => $randomMediumImage,
                    ]);
                }
            }

            for ($i = $amountOfMediumPictures + 1; $i <= $amountOfHardPictures; $i++) {
                $pictureAmount = $levelPictureAmount[$i];
                for ($j = 1; $j <= $pictureAmount; $j++) {
                    $randomHardImage = $images->where('difficulty', 3)->random()->name;
                    LevelImage::create([
                        'level_id' => $levels->where('name', $i)->first()->id,
                        'image_name' => $randomHardImage,
                    ]);
                }
            }
        }
    }
}
