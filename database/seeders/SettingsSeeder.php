<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Seed setting names: Music Volume, Sound Volume, Brightness Level
        $settings = [
            ['name' => 'music_volume'],
            ['name' => 'sound_volume'],
            ['name' => 'brightness_level'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
