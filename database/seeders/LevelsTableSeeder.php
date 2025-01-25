<?php

namespace Database\Seeders;

use App\Models\Episode;
use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levelRewards = [
            1 => 5,
            2 => 10,
            3 => 15,
            4 => 20,
            5 => 25,
            6 => 30,
            7 => 35,
            8 => 40,
            9 => 45,
            10 => 50,
            11 => 55,
            12 => 60,
            13 => 65,
            14 => 70,
            15 => 75,
            16 => 80,
            17 => 85,
            18 => 90,
            19 => 95,
            20 => 100,
        ];


        for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
            $episode = Episode::find($episodeNumber);

            for ($levelNumber = 1; $levelNumber <= 20; $levelNumber++) {
                Level::create([
                    'name' => $levelNumber,
                    'episode_id' => $episode->id,
                    'reward_coins' => $levelRewards[$levelNumber],
                ]);
            }
        }
    }
}
