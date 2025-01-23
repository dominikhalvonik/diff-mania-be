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
            ['day' => 1, 'coins' => 3],
            ['day' => 2, 'coins' => 5],
            ['day' => 3, 'coins' => 7],
            ['day' => 4, 'coins' => 10],
            ['day' => 5, 'coins' => 15],
            ['day' => 6, 'coins' => 20],
            ['day' => 7, 'coins' => 30],
        ];

        DailyRewardConfig::insert($rewards);

    }
}
