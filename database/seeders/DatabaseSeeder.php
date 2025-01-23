<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UserAttributeDefinitionSeeder::class);
        $this->call(EpisodeSeeder::class);
        $this->call(LevelsTableSeeder::class);
        $this->call(BoostersTableSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(GameConfigSeeder::class);
        $this->call(GameVersionSeeder::class);
        $this->call(LevelConfigSeeder::class);
        $this->call(RewardSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(TaskConfigSeeder::class);
        $this->call(LevelConfigSeeder::class);
        // $this->call(LevelImageSeeder::class);
    }
}
