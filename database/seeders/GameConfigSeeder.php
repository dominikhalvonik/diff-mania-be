<?php

namespace Database\Seeders;

use App\Models\GameConfig;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameConfigSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $levelImageDifferences = json_encode([
            'tier_1' => 5,
            'tier_2' => 7,
            'tier_3' => 10,
        ]);

        $bonusTimeConfigs = json_encode([
            'tier_1' => 30,
            'tier_2' => 40,
            'tier_3' => 60,
        ]);

        $levelImageAmount = json_encode([
            'easy' => 1,
            'medium' => 2,
            'hard' => 3,
        ]);

        $episodeTimeLimits = json_encode([
            'tier_1' => [
                1 => 150,
                2 => 148,
                3 => 146,
                4 => 144,
                5 => 142,
                6 => 140,
                7 => 138,
                8 => 136,
                9 => 134,
                10 => 132,
            ],
            'tier_2' => [
                1 => 210,
                2 => 205,
                3 => 200,
                4 => 195,
                5 => 190,
                6 => 185,
                7 => 180,
                8 => 175,
                9 => 170,
                10 => 165,
            ],
            'tier_3' => [
                1 => 250,
                2 => 245,
                3 => 240,
                4 => 235,
                5 => 230,
                6 => 225,
                7 => 220,
                8 => 215,
                9 => 210,
                10 => 205,
            ],
        ]);

        $numberOfPictures = json_encode([
            'episode_1' => [
                1 => ['difficulty' => 'easy', 'tier_1' => 1],
                2 => ['difficulty' => 'easy', 'tier_1' => 1],
                3 => ['difficulty' => 'easy', 'tier_1' => 1],
                4 => ['difficulty' => 'easy', 'tier_1' => 1],
                5 => ['difficulty' => 'easy', 'tier_1' => 1],
                6 => ['difficulty' => 'medium', 'tier_1' => 2],
                7 => ['difficulty' => 'easy', 'tier_1' => 1],
                8 => ['difficulty' => 'easy', 'tier_1' => 1],
                9 => ['difficulty' => 'easy', 'tier_1' => 1],
                10 => ['difficulty' => 'hard', 'tier_1' => 2, 'tier_2' => 1],
                11 => ['difficulty' => 'easy', 'tier_2' => 1],
                12 => ['difficulty' => 'medium', 'tier_1' => 1, 'tier_2' => 1],
                13 => ['difficulty' => 'easy', 'tier_2' => 1],
                14 => ['difficulty' => 'easy', 'tier_2' => 1],
                15 => ['difficulty' => 'easy', 'tier_2' => 1],
                16 => ['difficulty' => 'medium', 'tier_2' => 2],
                17 => ['difficulty' => 'easy', 'tier_2' => 1],
                18 => ['difficulty' => 'easy', 'tier_2' => 1],
                19 => ['difficulty' => 'easy', 'tier_2' => 1],
                20 => ['difficulty' => 'hard', 'tier_2' => 2, 'tier_3' => 1],
            ]
        ]);

        GameConfig::create([
            'name' => GameConfig::CORE_CONFIG,
            'description' => 'The Core game configuration',
            'value' => json_encode([
                'name' => 'Difference Mania',
                'max_lives' => 5,
                'lives_refill_time' => 120,
                'acoount_connection_reward' => 50,
            ])
        ]);

        GameConfig::create([
            'name' => GameConfig::EPISODE_TIME_LIMTS,
            'description' => 'Episode time limits configuration by tier',
            'value' => $episodeTimeLimits
        ]);

        GameConfig::create([
            'name' => GameConfig::BONUS_TIME_CONFIG,
            'description' => 'Episode bonus time configuration by tier',
            'value' => $bonusTimeConfigs
        ]);

        GameConfig::create([
            'name' => GameConfig::LEVEL_IMAGE_AMOUNT_BY_DIFFICULTY,
            'description' => 'Level image amount configuration by tier',
            'value' => $levelImageAmount
        ]);

        GameConfig::create([
            'name' => GameConfig::LEVEL_IMAGE_DIFFERENCES,
            'description' => 'Level image differences configuration by hardness',
            'value' => $levelImageDifferences
        ]);

        GameConfig::create([
            'name' => GameConfig::NUMBER_OF_PICTURES_PER_EPISODE,
            'description' => 'Number of pictures configuration by episode and tier',
            'value' => $numberOfPictures
        ]);

    }
}
