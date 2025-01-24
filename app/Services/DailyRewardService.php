<?php

namespace App\Services;

use App\Models\DailyReward;
use App\Models\DailyRewardConfig;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DailyRewardService
{

  public function getDailyRewards(User $user)
  {
    // Get the last login
    $lastLogin = $user->userAttributes->where('user_attribute_definition_id', User::LAST_LOGIN_DATE)->first()->value;

    $dailyRewardConfig = Cache::remember(DailyRewardConfig::REWARD_CONFIG_CACHE_KEY, 60, function () {
      return DailyRewardConfig::all()->mapWithKeys(function ($dailyRewardConf) {
        return [$dailyRewardConf->day => $dailyRewardConf->reward_coins];
      });
    });

    $userDailyRewards = DailyReward::where('user_id', $user->id)->get();

    // Check if the user has logged in today
    if (date('Y-m-d', $lastLogin) === date('Y-m-d')) {
      $dailyRewards = $userDailyRewards->map(function ($dailyReward) use ($dailyRewardConfig) {
        return [
          "day" => $dailyReward->day,
          "reward" => $dailyRewardConfig[$dailyReward->day],
          "opened" => $dailyReward->opened
        ];
      });

      return ['dailyRewards' => $dailyRewards];
    }

    $yesterday = strtotime('yesterday');

    // If the user was logged in last time yesterday then the main logic comes
    if (date("d", $lastLogin) === date("d", $yesterday)) {

      if ($userDailyRewards->isEmpty()) {
        $dailyRewards = [
          ["day" => 1, "reward" => $dailyRewardConfig[1], "opened" => false],
        ];

        DailyReward::create([
          'user_id' => $user->id,
          'day' => 1,
          'opened' => false
        ]);

        $this->updateLastLogin($user);

        return ['dailyRewards' => $dailyRewards];
      }

      $lastReward = $userDailyRewards->max('day');

      // Check if last reward is between 2 and 6
      if ($lastReward >= 1 && $lastReward < 7) {

        $dailyRewards = $userDailyRewards->map(function ($dailyReward) use ($dailyRewardConfig) {
          return [
            "day" => $dailyReward->day,
            "reward" => $dailyRewardConfig[$dailyReward->day],
            "opened" => $dailyReward->opened
          ];
        });

        $newReward = $lastReward + 1;

        DailyReward::create([
          'user_id' => $user->id,
          'day' => $newReward,
          'opened' => false
        ]);

        $dailyRewards->push(["day" => $newReward, "reward" => $dailyRewardConfig[$newReward], "opened" => 0]);

        $this->updateLastLogin($user);

        return ['dailyRewards' => $dailyRewards];
      }

      // Case of the last reward
      if ($lastReward === 7) {

        $dailyRewards = $userDailyRewards->map(function ($dailyReward) use ($dailyRewardConfig) {
          return [
            "day" => $dailyReward->day,
            "reward" => $dailyRewardConfig[$dailyReward->day],
            "opened" => $dailyReward->opened
          ];
        });

        return ['dailyRewards' => $dailyRewards];
      }


    } else {

      // There are more then 1 day difference - we delete every daily reward of user
      $userDailyRewards->each(function ($dailyReward) {
        $dailyReward->delete();
      });

      return ['dailyRewards' => []];
    }
  }

  private function updateLastLogin(User $user)
  {
    $user->userAttributes()->where('user_attribute_definition_id', User::LAST_LOGIN_DATE)->update(['value' => time()]);
  }
}