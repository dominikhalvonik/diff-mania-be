<?php

namespace Database\Seeders;

use App\Models\Episode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EpisodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $episodes = [
            ['Episode 1', 0, 0],
            ['Episode 2', 30, 50],
            ['Episode 3', 33, 75],
            ['Episode 4', 36, 100],
            ['Episode 5', 39, 125],
            ['Episode 6', 42, 150],
            ['Episode 7', 45, 175],
            ['Episode 8', 48, 200],
            ['Episode 9', 51, 225],
            ['Episode 10', 54, 250],
        ];

        for ($i = 0; $i < count($episodes); $i++) {
            Episode::create([
                'unlock_stars' => $episodes[$i][1],
                'unlock_coins' => $episodes[$i][2],
            ]);
        }
    }
}
