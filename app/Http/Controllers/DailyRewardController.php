<?php

namespace App\Http\Controllers;

use App\Models\DailyReward;
use Illuminate\Http\Request;
use App\Models\DailyRewardConfig;
use Illuminate\Support\Facades\Cache;
use App\Models\User;


class DailyRewardController extends Controller
{
    public function claimReward(Request $request, int $rewardDay)
    {
        $user = $request->user();

        $dailyReward = DailyReward::where('user_id', $user->id)
            ->where('day', $rewardDay)
            ->where('opened', false)
            ->first();

        if (!$dailyReward) {
            return response()->json(['message' => 'Reward already claimed or not available'], 400);
        }

        // Add the coins based on config
        $dailyRewardConfig = Cache::remember(DailyRewardConfig::REWARD_CONFIG_CACHE_KEY, 60, function () {
            return DailyRewardConfig::all()->mapWithKeys(function ($dailyRewardConf) {
                return [$dailyRewardConf->day => $dailyRewardConf->reward_coins];
            });
        });

        $user->load('userAttributes');

        // get user attribute coins
        $userCoinsAmount = $user->userAttributes->where('user_attribute_definition_id', User::COINS)->first()->value;

        $userCoinsAmount += $dailyRewardConfig[$dailyReward->day];

        $user->userAttributes()->where('user_attribute_definition_id', User::COINS)->update(['value' => $userCoinsAmount]);

        $dailyReward->claim();

        return response()->json($dailyReward);
    }
}
