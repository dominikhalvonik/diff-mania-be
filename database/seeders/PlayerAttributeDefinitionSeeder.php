<?php

namespace Database\Seeders;

use App\Models\PlayerAttributeDefinition;
use Illuminate\Database\Seeder;

class PlayerAttributeDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Seed basic player attributes like level, experience, coins, health
        $attributes = [
            ['name' => 'level', 'description' => 'Player level', 'default_value' => 1],
            ['name' => 'experience', 'description' => 'Player experience', 'default_value' => 0],
            ['name' => 'coins', 'description' => 'Player coins', 'default_value' => 100],
            ['name' => 'lives', 'description' => 'Player lives', 'default_value' => 5],
            ['name' => 'has_ads_removed', 'description' => 'The user has bought the adremover offer', 'default_value' => 0],
            ['name' => 'last_refill_timestamp', 'description' => 'The last time lives were refilled', 'default_value' => 0],
            ['name' => 'free_nickname_available', 'description' => 'Has free nickname change available', 'default_value' => 1],
            ['name' => 'rewarded_for_acc_connection', 'description' => 'If the player took reward for connecting his account', 'default_value' => 0]
        ];

        foreach ($attributes as $attribute) {
            PlayerAttributeDefinition::create($attribute);
        }
    }
}
