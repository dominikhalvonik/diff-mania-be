<?php

namespace Database\Seeders;

use App\Models\Booster;
use Illuminate\Database\Seeder;

class BoostersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booster::create([
            'name' => 'hint',
        ]);

        Booster::create([
            'name' => 'bonus_time',
        ]);
    }
}
