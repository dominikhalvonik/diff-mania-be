<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reward;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed some random rewards based on rewards table migration and user_attribute_definitions seeder
        $rewards = [
            ['user_attribute_definition_id' => 1, 'amount' => 100],
            ['user_attribute_definition_id' => 2, 'amount' => 200],
            ['user_attribute_definition_id' => 3, 'amount' => 300],
            ['user_attribute_definition_id' => 1, 'amount' => 500],
            ['user_attribute_definition_id' => 2, 'amount' => 400],
            ['user_attribute_definition_id' => 3, 'amount' => 200],
        ];

        foreach ($rewards as $reward) {
            Reward::create($reward);
        }
    }
}
