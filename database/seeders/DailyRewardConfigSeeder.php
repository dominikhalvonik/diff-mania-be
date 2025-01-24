<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DailyRewardConfig;

class DailyRewardConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $rewards = [
            ['day' => 1, 'reward_coins' => 3],
            ['day' => 2, 'reward_coins' => 5],
            ['day' => 3, 'reward_coins' => 7],
            ['day' => 4, 'reward_coins' => 10],
            ['day' => 5, 'reward_coins' => 15],
            ['day' => 6, 'reward_coins' => 20],
            ['day' => 7, 'reward_coins' => 30],
        ];

        DailyRewardConfig::insert($rewards);

    }
}
