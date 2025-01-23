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
            ['level' => 1, 'experience' => 0, 'coin_reward' => 0],
            ['level' => 2, 'experience' => 500, 'coin_reward' => 10],
            ['level' => 3, 'experience' => 1000, 'coin_reward' => 30],
            ['level' => 4, 'experience' => 2000, 'coin_reward' => 40],
            ['level' => 5, 'experience' => 3000, 'coin_reward' => 50],
            ['level' => 6, 'experience' => 5000, 'coin_reward' => 60],
            ['level' => 7, 'experience' => 7000, 'coin_reward' => 70],
            ['level' => 8, 'experience' => 10000, 'coin_reward' => 80],
            ['level' => 9, 'experience' => 15000, 'coin_reward' => 90],
            ['level' => 10, 'experience' => 20000, 'coin_reward' => 100],
            ['level' => 11, 'experience' => 25000, 'coin_reward' => 100],
            ['level' => 12, 'experience' => 30000, 'coin_reward' => 100],
            ['level' => 13, 'experience' => 35000, 'coin_reward' => 100],
            ['level' => 14, 'experience' => 40000, 'coin_reward' => 100],
            ['level' => 15, 'experience' => 45000, 'coin_reward' => 100],
            ['level' => 16, 'experience' => 50000, 'coin_reward' => 100],
            ['level' => 17, 'experience' => 55000, 'coin_reward' => 100],
            ['level' => 18, 'experience' => 60000, 'coin_reward' => 100],
            ['level' => 19, 'experience' => 65000, 'coin_reward' => 100],
            ['level' => 20, 'experience' => 70000, 'coin_reward' => 100],
            ['level' => 21, 'experience' => 75000, 'coin_reward' => 100],
            ['level' => 22, 'experience' => 80000, 'coin_reward' => 100],
            ['level' => 23, 'experience' => 85000, 'coin_reward' => 100],
            ['level' => 24, 'experience' => 90000, 'coin_reward' => 100],
            ['level' => 25, 'experience' => 95000, 'coin_reward' => 100],
            ['level' => 26, 'experience' => 100000, 'coin_reward' => 100],
            ['level' => 27, 'experience' => 105000, 'coin_reward' => 100],
            ['level' => 28, 'experience' => 110000, 'coin_reward' => 100],
            ['level' => 29, 'experience' => 115000, 'coin_reward' => 100],
            ['level' => 30, 'experience' => 120000, 'coin_reward' => 100],
            ['level' => 31, 'experience' => 125000, 'coin_reward' => 100],
            ['level' => 32, 'experience' => 130000, 'coin_reward' => 100],
            ['level' => 33, 'experience' => 135000, 'coin_reward' => 100],
            ['level' => 34, 'experience' => 140000, 'coin_reward' => 100],
            ['level' => 35, 'experience' => 145000, 'coin_reward' => 100],
            ['level' => 36, 'experience' => 150000, 'coin_reward' => 100],
            ['level' => 37, 'experience' => 155000, 'coin_reward' => 100],
            ['level' => 38, 'experience' => 160000, 'coin_reward' => 100],
            ['level' => 39, 'experience' => 165000, 'coin_reward' => 100],
            ['level' => 40, 'experience' => 170000, 'coin_reward' => 100],
            ['level' => 41, 'experience' => 175000, 'coin_reward' => 100],
            ['level' => 42, 'experience' => 180000, 'coin_reward' => 100],
            ['level' => 43, 'experience' => 185000, 'coin_reward' => 100],
            ['level' => 44, 'experience' => 190000, 'coin_reward' => 100],
            ['level' => 45, 'experience' => 195000, 'coin_reward' => 100],
            ['level' => 46, 'experience' => 200000, 'coin_reward' => 100],
            ['level' => 47, 'experience' => 205000, 'coin_reward' => 100],
            ['level' => 48, 'experience' => 210000, 'coin_reward' => 100],
            ['level' => 49, 'experience' => 215000, 'coin_reward' => 100],
            ['level' => 50, 'experience' => 220000, 'coin_reward' => 100],
        ];

        foreach ($levelConfigs as $levelConfig) {
            LevelConfig::create($levelConfig);
        }
    }
}
