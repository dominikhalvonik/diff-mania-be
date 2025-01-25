<?php

namespace App\Services;

use App\Models\Episode;
use Illuminate\Support\Facades\Cache;


class ProgressService
{
  public function getEpisodesWithLevels($user)
  {
    $progress = $user->userLevelProgress()->get();
    $progress = $progress->groupBy('level_id');

    // Get all episodes with associated levels and cache it to redis
    $episodes = Cache::remember('episodes', 120, function () {
      return Episode::with('levels')->get();
    });

    // If the user has no progress data then return the episodes with the associated levels and
    if ($progress->isEmpty()) {
      $episodes->each(function ($episode) {
        $episode->levels->each(function ($level) {
          $level->setAttribute('stars_collected', 0);
        });
      });

      return $episodes;
    }

    // If the user has progress data then return the episodes with the associated
    $episodes->each(function ($episode) use ($progress) {
      $episode->levels->each(function ($level) use ($progress) {
        $level->setAttribute('stars_collected', $progress->get($level->id)?->first()?->stars_collected ?? 0);
      });
    });

    return $episodes;
  }
}
