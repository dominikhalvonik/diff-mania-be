<?php

namespace Database\Seeders;

use App\Models\LevelConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $levelConfigs = [
            ['level_achieved' => 1, 'experience_required' => 0],
            ['level_achieved' => 2, 'experience_required' => 1000],
            ['level_achieved' => 3, 'experience_required' => 2000],
            ['level_achieved' => 4, 'experience_required' => 3000],
            ['level_achieved' => 5, 'experience_required' => 4000],
            ['level_achieved' => 6, 'experience_required' => 5000],
            ['level_achieved' => 7, 'experience_required' => 6000],
            ['level_achieved' => 8, 'experience_required' => 7000],
            ['level_achieved' => 9, 'experience_required' => 8000],
            ['level_achieved' => 10, 'experience_required' => 15000],
        ];

        foreach ($levelConfigs as $levelConfig) {
            LevelConfig::create($levelConfig);
        }
    }
}
