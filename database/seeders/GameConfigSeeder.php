<?php

namespace Database\Seeders;

use App\Models\GameConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GameConfig::create([
            'name' => GameConfig::CORE_CONFIG,
            'description' => 'The Core game configuration',
            'value' => json_encode([
                'name' => 'Difference Mania',
                'max_lives' => 5,
                'lives_refill_time' => 5,
            ])
        ]);
    }
}
