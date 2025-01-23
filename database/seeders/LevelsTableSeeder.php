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
        for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {
            $episode = Episode::find($episodeNumber);

            for ($levelNumber = 1; $levelNumber <= 20; $levelNumber++) {
                Level::create([
                    'episode_id' => $episode->id,
                    'reward_coins' => rand(100, 1000),
                ]);
            }
        }
    }
}
