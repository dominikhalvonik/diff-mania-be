<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class GameConfig extends Model
{
    // Create a static const with the value 'core_config'
    public const CORE_CONFIG = 'core_config';
    public const LEVEL_IMAGE_AMOUNT_BY_DIFFICULTY = 'level_image_amount_BY_DIFFICULTY';
    public const BONUS_TIME_CONFIG = 'bonus_time_config';
    public const LEVEL_IMAGE_DIFFERENCES = 'level_image_differences';
    public const EPISODE_TIME_LIMTS = 'episode_time_limits';

    public const NUMBER_OF_PICTURES_PER_EPISODE = 'number_of_pictures_per_episode';


    public function getCoreConfig()
    {
        return Cache::remember('core_config', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::CORE_CONFIG)->first()->value);
        });
    }

    public function getLevelImageAmount()
    {
        return Cache::remember('level_image_amount', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::LEVEL_IMAGE_AMOUNT_BY_DIFFICULTY)->first()->value);
        });
    }

    public function getBonusTimeConfig()
    {
        return Cache::remember('bonus_time_config', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::BONUS_TIME_CONFIG)->first()->value);
        });
    }

    public function getLevelImageDifferences()
    {
        return Cache::remember('level_image_differences', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::LEVEL_IMAGE_DIFFERENCES)->first()->value);
        });
    }

    public function getEpisodeTimeLimits()
    {
        return Cache::remember('episode_time_limits', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::EPISODE_TIME_LIMTS)->first()->value);
        });
    }

    public function getNumberOfPicturesPerEpisode()
    {
        return Cache::remember('number_of_pictures_per_episode', LONG_CACHE_TIME, function () {
            return json_decode(GameConfig::where('name', GameConfig::NUMBER_OF_PICTURES_PER_EPISODE)->first()->value);
        });
    }
}
