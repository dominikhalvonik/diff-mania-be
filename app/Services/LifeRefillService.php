<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\GameConfig;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LifeRefillService
{
  public function checkAndRefill(User $user)
  {
    // Get the config
    $config = Cache::remember(GameConfig::CORE_CONFIG, 60, function () {
      return GameConfig::where('name', GameConfig::CORE_CONFIG)->first();
    });

    $config = json_decode($config->value);
    $maxLives = $config->max_lives;
    $refillInterval = $config->lives_refill_time;

    $currentLives = $user->userAttributes->where('user_attribute_definition_id', User::LIVES)->first()->value;
    $lastRefillTime = $user->userAttributes->where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->first()->value;
    $currentTime = now();

    if ($lastRefillTime === 0) {
      $user->userAttributes->where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->first()->update(['value' => $lastRefillTime]);

      return [
        'max_lives' => $maxLives,
        'lives' => $currentLives,
        'life_refill_time' => null,
      ];
    }

    $secondsToNextRefill = 0;

    if ($currentLives < $maxLives) {
      $lastRefillTime = Carbon::parse($lastRefillTime);
      $diff = $lastRefillTime->diffInMinutes($currentTime);
      $livesToRefill = floor($diff / $refillInterval);

      if ($livesToRefill > 0) {
        $newLives = min($currentLives + $livesToRefill, $maxLives);
        $currentLives = $newLives;

        $user->userAttributes->where('user_attribute_definition_id', User::LIVES)->first()->update(['value' => $newLives]);
        $user->userAttributes->where('user_attribute_definition_id', User::LAST_REFILL_TIMER)->first()->update(['value' => time()]);
      }

      // if there is still some lives to refill calculate the time to next refill
      if ($currentLives < $maxLives) {
        $diffInSeconds = $lastRefillTime->diffInSeconds($currentTime);
        $refillIntervalInSeconds = $refillInterval * 60;

        $secondsToNextRefill = ($refillIntervalInSeconds - ($diffInSeconds % $refillIntervalInSeconds));
      }
    }

    return [
      'max_lives' => $maxLives,
      'lives' => $currentLives,
      'life_refill_time' => $currentLives < $maxLives ? $secondsToNextRefill : null
    ];
  }
}
