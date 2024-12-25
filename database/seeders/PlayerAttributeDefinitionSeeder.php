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
            ['name' => 'health', 'description' => 'Player health', 'default_value' => 5],
        ];

        foreach ($attributes as $attribute) {
            PlayerAttributeDefinition::create($attribute);
        }
    }
}
