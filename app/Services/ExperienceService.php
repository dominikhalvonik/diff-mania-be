<?php
namespace App\Services;

use App\Models\LogTable;
use App\Models\User;
use App\Models\LevelConfig;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ExperienceService
{

  private const TEN_DAYS = 60 * 24 * 10;
  private const LEVEL_UP_CONFIG = 'level_up_config';

  public function actualizeExperience(User $user, int $experienceGained)
  {
    // Check if the user has leveled up
    $levelUpConfig = Cache::remember($this::LEVEL_UP_CONFIG, self::TEN_DAYS, function () {
      return LevelConfig::pluck('experience', 'level')->toArray();
    });

    $user->load('userAttributes');

    $currentLevel = $user->userAttributes->where('user_attribute_definition_id', User::LEVEL)->first()->value;

    // Add the experience gained to the user's current experience
    $userExperienceAttribute = $user->userAttributes->where('user_attribute_definition_id', User::EXPERIENCE)->first()->value;
    $userExperienceAttribute += $experienceGained;
    $user->userAttributes()->where('user_attribute_definition_id', User::EXPERIENCE)->update(['value' => $userExperienceAttribute]);

    $nextLevelExperience = $levelUpConfig[$currentLevel + 1] ?? null;


    LogTable::create([
      'user_id' => $user->id,
      'log_info' => 'Gained experience',
      'value' => $experienceGained
    ]);

    if ($nextLevelExperience && $userExperienceAttribute >= $nextLevelExperience) {
      // User has leveled up
      $currentLevel += 1;

      $rewardsAmount = $this->getLevelUpRewards($currentLevel);

      $userCoinsAttribute = $user->userAttributes->where('user_attribute_definition_id', User::COINS)->first()->value;
      $userCoinsAttribute += $rewardsAmount;

      DB::transaction(function () use ($user, $currentLevel, $userCoinsAttribute, $rewardsAmount) {
        $user->userAttributes()->where('user_attribute_definition_id', User::LEVEL)->update(['value' => $currentLevel]);
        $user->userAttributes()->where('user_attribute_definition_id', User::COINS)->update(['value' => $userCoinsAttribute]);

        LogTable::create([
          'user_id' => $user->id,
          'log_info' => 'User Leveled up',
          'value' => $currentLevel
        ]);

        LogTable::create([
          'user_id' => $user->id,
          'log_info' => 'Gained rewarded coins',
          'value' => $rewardsAmount
        ]);
      });

      return ['level' => ['level_up' => true, 'rewards' => ['coins' => $rewardsAmount]]];
    }

    // User has not leveled up
    return ['level' => ['level_up' => false, 'rewards' => ['coins' => 0]]];
  }

  private function getLevelUpRewards(int $level)
  {
    // Define rewards based on level
    $rewardsConfig = Cache::remember('level_rewards', self::TEN_DAYS, function () {
      return LevelConfig::pluck('coin_reward', 'level')->toArray();
    });
    return $rewardsConfig[$level + 1] ?? [];
  }

  public function calculateExperienceForNextLevel(User $user)
  {
    $levelUpConfig = Cache::remember($this::LEVEL_UP_CONFIG, self::TEN_DAYS, function () {
      return LevelConfig::pluck('experience', 'level')->toArray();
    });

    $user->load('userAttributes');

    $currentLevel = $user->userAttributes->where('user_attribute_definition_id', User::LEVEL)->first()->value;
    $userExperienceAttribute = $user->userAttributes->where('user_attribute_definition_id', User::EXPERIENCE)->first()->value;

    $nextLevelExperience = $levelUpConfig[$currentLevel + 1] ?? null;

    if ($nextLevelExperience) {
      return $nextLevelExperience - $userExperienceAttribute;
    }

    return 0;
  }
}