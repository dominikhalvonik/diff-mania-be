<?php

namespace App\Services;

use App\Models\GameConfig;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

use App\Services\DailyRewardService;
use App\Services\LifeRefillService;

class UserService
{


  public function __construct(
    private readonly DailyRewardService $dailyRewardService,
    private readonly LifeRefillService $lifeRefillService,
    private readonly ExperienceService $experienceService
  ) {
  }

  // Just to not forget what I do not return and should
  //     {
  //       {
  //             "currentWins": 2, //toto je kolko ma vyhier do dalsieho darceku
  //             "winsNeededToNextGift": 6, //toto neviem ci je staticke, že kazdych 6 winov otvaras darceky alebo sa to stupnuje
  //             "selectedPFP": 2,  //obrazok profilu, asi idealne budu IDcka(int)
  //             "unlockedPFP": [1,2,3,69], //pole odomknutych
  //             //tasky
  //             "tasks": [{
  //                 "findXdifferences" : {
  //                     "progress": 2,
  //                     "needToComplete": 30,
  //                     "reward": 10 //toto bude vzdy coins    
  //                 },
  //                 "finishXpictures":{
  //                     "progress": 2,
  //                     "needToComplete": 3,
  //                     "reward": 10 //toto bude vzdy coins
  //                 },
  //                 "useXhints":{
  //                     "progress": 2,
  //                     "needToComplete": 5,
  //                     "reward": 10 //toto bude vzdy coins
  //                 },
  //                 "useXboosts":{
  //                     "progress": 2,
  //                     "needToComplete": 5,
  //                     "reward": 10 //toto bude vzdy coins
  //                 },
  //             }],
  //             //collection
  //             "collection": [{
  //                 "id": 1,
  //                 "name": "Trophy",
  //                 "progress": 1 //0 - nič, 1- jedna časť, 2, 3 - cely kus
  //             },
  //             {
  //                 "id": 2,
  //                 "name": "Hourglass",
  //                 "progress": 3
  //           }]
  //         }
  public function loadInitData(User $user)
  {

    // Check Life status
    $lifeStatus = $this->lifeRefillService->checkAndRefill($user);

    // Add the attributes
    $attributes = $user->userAttributes->mapWithKeys(function ($attribute) {
      return [$attribute->userAttributeDefinition->name => $attribute->value];
    });

    // Combine the data
    $attributes['max_lives'] = $lifeStatus['max_lives'];
    $attributes['lives'] = $lifeStatus['lives'];
    $attributes['life_refill_time'] = $lifeStatus['life_refill_time'];

    // User boosters
    $userBoosters = $user->userBoosters->mapWithKeys(function ($userBooster) {
      return [$userBooster->booster->name => $userBooster->quantity];
    });

    if ($userBoosters->isEmpty()) {
      $attributes['boost_bonus_time'] = 0;
      $attributes['boost_hint'] = 0;
    } else {
      $attributes['boost_bonus_time'] = $userBoosters['bonus_time'];
      $attributes['boost_hint'] = $userBoosters['hint'];
    }

    // Daily rewards calculator
    $dailyReward = $this->dailyRewardService->getDailyRewards($user);

    // Experience
    $experienceToNextLevel = $this->experienceService->calculateExperienceForNextLevel($user);
    $attributes['experience_to_next_level'] = $experienceToNextLevel;

    // User tasks
    // Check if the user has any tasks and if not then create them from task-configs


    // Remove unused attributes
    unset($attributes['last_login']);

    // Return the expected format
    return [
      'name' => $user->name,
      'email' => $user->email,
      'nickname' => $user->nickname,
      'stars_collected' => $user->getUserStarsCollectedCount(),
      'finished_levels' => $user->userLevelProgress->count(),
      ...$attributes,
      ...$dailyReward
    ];
  }
}
