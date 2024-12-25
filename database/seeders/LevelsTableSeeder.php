<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Levels;
use App\Models\Episodes;

class LevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Episodes and save them to databases. Use the created episodes to create levels.
        $episodes = Episodes::factory(5)->create();
        foreach ($episodes as $episode) {
            Levels::factory(5)->create(['episode_id' => $episode->id]);
        }
    }
}
