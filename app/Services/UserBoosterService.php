<?php

namespace App\Services;

use App\Models\LogTable;
use App\Models\User;
use App\Models\Booster;
use App\Models\UserBooster;

class UserBoosterService
{
  public function addBooster(User $user, Booster $booster, int $amount)
  {
    try {
      $userBooster = UserBooster::firstOrCreate(
        ['user_id' => $user->id, 'booster_id' => $booster->id],
        ['quantity' => 0]
      );

      $userBooster->quantity += $amount;
      $userBooster->save();

      LogTable::create([
        'log_info' => 'Booster added successfully',
        'user_id' => $user->id,
        'value' => $amount,
      ]);

      return true;
    } catch (\Exception $e) {
      LogTable::create([
        'log_info' => $e->getMessage(),
        'user_id' => $user->id,
      ]);
      return false;
    }
  }

  public function removeBooster(User $user, Booster $booster, int $amount)
  {
    try {
      $userBooster = UserBooster::where('user_id', $user->id)
        ->where('booster_id', $booster->id)
        ->first();

      if ($userBooster) {
        $userBooster->quantity -= $amount;
        if ($userBooster->quantity < 0) {
          $userBooster->quantity = 0;

          return false;
        }
        $userBooster->save();
      }

      LogTable::create([
        'log_info' => 'Booster removed successfully',
        'user_id' => $user->id,
        'value' => $amount,
      ]);

      return true;
    } catch (\Exception $e) {
      LogTable::create([
        'log_info' => $e->getMessage(),
        'user_id' => $user->id,
      ]);
      return false;
    }
  }
}