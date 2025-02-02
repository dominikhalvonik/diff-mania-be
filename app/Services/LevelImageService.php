<?php

namespace App\Services;

use App\Models\GameConfig;
use App\Models\Level;
use Cache;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\Image;

class LevelImageService
{
  protected $config;

  public function __construct(private readonly GameConfig $gameConfig)
  {
  }

  public function getLevelImagesFromConfig(Level $level): array
  {
    $levelEpisodeId = $level->load('episode')->episode->id;

    $numberOfPicturesPerEpisode = $this->gameConfig->getNumberOfPicturesPerEpisode();

    $currentEpisodeConfig = $numberOfPicturesPerEpisode->{"episode_$levelEpisodeId"};

    if (!$currentEpisodeConfig) {
      Log::error("No Episode Config found for Episode: {$levelEpisodeId}");
      return [];
    }

    $currentLevelConfig = $currentEpisodeConfig->{$level->name};

    if (!$currentLevelConfig) {
      Log::error("No Level Config found for Level: {$level->name}");
      return [];
    }

    $allImages = Cache::remember('all_images', LONG_CACHE_TIME, function () {
      return Image::all();
    });

    // Get the images for the current level by the config $currentLevelConfig
    $easyImageFilter = fn($image) => $image->difficulty === 1;
    $mediumImageFilter = fn($image) => $image->difficulty === 2;
    $hardImageFilter = fn($image) => $image->difficulty === 3;

    $easyImages = $allImages->filter($easyImageFilter);
    $mediumImages = $allImages->filter($mediumImageFilter);
    $hardImages = $allImages->filter($hardImageFilter);

    $images = [];

    $tier1ImageCount = $currentLevelConfig->tier_1 ?? 0;
    $tier2ImageCount = $currentLevelConfig->tier_2 ?? 0;
    $tier3ImageCount = $currentLevelConfig->tier_3 ?? 0;

    if ($tier1ImageCount !== 0) {
      if ($easyImages->count() < $tier1ImageCount) {
        Log::error(message: "Not enough easy images available for Level: {$level->name}");
        throw new Exception("Not enough hard images available for Level: {$level->name}");
      }
      $images = $easyImages->shuffle()->take($tier1ImageCount);
    }

    if ($tier2ImageCount !== 0) {
      if ($mediumImages->count() < $tier2ImageCount) {
        Log::error("Not enough medium images available for Level: {$level->name}");
        throw new Exception("Not enough hard images available for Level: {$level->name}");
      }
      $images = $images->merge($mediumImages->shuffle()->take($tier2ImageCount));
    }

    if ($tier3ImageCount !== 0) {
      if ($hardImages->count() < $tier3ImageCount) {
        Log::error("Not enough hard images available for Level: {$level->name}");
        throw new Exception("Not enough hard images available for Level: {$level->name}");
      }
      $images = $images->merge($hardImages->shuffle()->take($tier3ImageCount));
    }

    $bonusTimeConfig = $this->gameConfig->getBonusTimeConfig();

    $episodeTimeLimits = $this->gameConfig->getEpisodeTimeLimits();

    // Calculate the time limit for the level and the 1_star, 2_star, 3_star times 
    $tier1Time = $tier1ImageCount * ($episodeTimeLimits->tier_1->{$level->id} ?? 0);
    $tier2Time = $tier2ImageCount * ($episodeTimeLimits->tier_2->{$level->id} ?? 0);
    $tier3Time = $tier3ImageCount * ($episodeTimeLimits->tier_3->{$level->id} ?? 0);

    $totalTimeLimit = $tier1Time + $tier2Time + $tier3Time;

    $oneStarTime = $totalTimeLimit * 0.8;
    $twoStarTime = $totalTimeLimit * 0.6;
    $threeStarTime = $totalTimeLimit * 0.4;

    // Calculate the bonus_time_limit for the level and the 1_star, 2_star, 3_star times
    $tier1BonusTime = $tier1ImageCount * ($bonusTimeConfig->tier_1 ?? 0);
    $tier2BonusTime = $tier2ImageCount * ($bonusTimeConfig->tier_2 ?? 0);
    $tier3BonusTime = $tier3ImageCount * ($bonusTimeConfig->tier_3 ?? 0);

    $totalBonusTime = $tier1BonusTime + $tier2BonusTime + $tier3BonusTime;

    $totalTimeLimitWithBonus = $totalTimeLimit + $totalBonusTime;

    $oneStarTimeWithBonus = $totalTimeLimitWithBonus * 0.8;
    $twoStarTimeWithBonus = $totalTimeLimitWithBonus * 0.6;
    $threeStarTimeWithBonus = $totalTimeLimitWithBonus * 0.4;

    Log::info($images[0]);
    // Decode the json in images
    $images = $images->map(function ($image) {
      Log::info($image);
      $decodedDifferences = json_decode($image->json_differences, true);
      $image->json_differences = $decodedDifferences['differences'] ?? [];
      return $image;
    });
    Log::info($images);

    return [
      'total_time_limit' => $totalTimeLimit,
      '1_star' => round($oneStarTime),
      '2_star' => round($twoStarTime),
      '3_star' => round($threeStarTime),
      'bonus_total_time_limit' => $totalTimeLimitWithBonus,
      '1_star_bonus_time' => round($oneStarTimeWithBonus),
      '2_star_bonus_time' => round($twoStarTimeWithBonus),
      '3_star_bonus_time' => round($threeStarTimeWithBonus),
      'images' => $images
    ];
  }
}