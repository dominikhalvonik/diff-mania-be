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
        // Create Episodes and save them to databases. Use the created episodes to create levels.
        $episodes = Episode::factory(5)->create();
        foreach ($episodes as $episode) {
            Level::factory(5)->create(['episode_id' => $episode->id]);
        }
    }
}
