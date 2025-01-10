<?php

namespace Database\Seeders;

use App\Models\UserAttributeDefinition;
use Illuminate\Database\Seeder;

class UserAttributeDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Seed basic user attributes like level, experience, coins, health
        $attributes = [
            ['name' => 'level', 'description' => 'User level', 'default_value' => 1],
            ['name' => 'experience', 'description' => 'User experience', 'default_value' => 0],
            ['name' => 'coins', 'description' => 'User coins', 'default_value' => 100],
            ['name' => 'lives', 'description' => 'User lives', 'default_value' => 5],
            ['name' => 'has_ads_removed', 'description' => 'The user has bought the adremover offer', 'default_value' => 0],
            ['name' => 'last_refill_timestamp', 'description' => 'The last time lives were refilled', 'default_value' => 0],
            ['name' => 'free_nickname_available', 'description' => 'Has free nickname change available', 'default_value' => 1],
            ['name' => 'rewarded_for_acc_connection', 'description' => 'If the user took reward for connecting his account', 'default_value' => 0]
        ];

        foreach ($attributes as $attribute) {
            UserAttributeDefinition::create($attribute);
        }
    }
}
