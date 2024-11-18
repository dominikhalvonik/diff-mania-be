<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerAttributesDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Seed basic player attributes like level, experience, coins, health
        $attributes = [
            ['name' => 'Level', 'description' => 'Player level', 'default_value' => 1],
            ['name' => 'Experience', 'description' => 'Player experience', 'default_value' => 0],
            ['name' => 'Coins', 'description' => 'Player coins', 'default_value' => 100],
            ['name' => 'Health', 'description' => 'Player health', 'default_value' => 5],
        ];

        foreach ($attributes as $attribute) {
            \App\Models\PlayerAttributesDefinitions::create($attribute);
        }
    }
}
