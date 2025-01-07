<?php

namespace Database\Seeders;

use App\Models\LevelImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LevelImage::create([
            'level_id' => 1,
            'image_name' => 1,
        ]);
    }
}
