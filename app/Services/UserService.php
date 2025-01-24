<?php

namespace App\Services;

use App\Models\GameConfig;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

use App\Services\DailyRewardService;

class UserService
{


  public function __construct(private readonly DailyRewardService $dailyRewardService)
  {
  }

  // Just to not forget what I do not return and should
  //     {
  //       {
  //             "timeForRefillLife": 200, 
  //             // "experienceToNextLevel": 69, //toto by nemuselo byt pri tomto api calle, mozno bude lepsie ak si nataham cely config co sa tyka experience/level/reward za level up 
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
    // Get the config
    $config = Cache::remember(GameConfig::CORE_CONFIG, 60, function () {
      return GameConfig::where('name', GameConfig::CORE_CONFIG)->first();
    });
    $config = json_decode($config->value);
    $maxLives = $config->max_lives;

    // Add the attributes
    $attributes = $user->userAttributes->mapWithKeys(function ($attribute) {
      return [$attribute->userAttributeDefinition->name => $attribute->value];
    });

    // Combine the data
    $attributes['max_lives'] = $maxLives;

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

    // User tasks
    // Check if the user has any tasks and if not then create them from task-configs

    // Return the expected format
    return [
      'name' => $user->name,
      'email' => $user->email,
      'nickname' => $user->nickname,
      'level_count' => $user->getLevelProgressCount(),
      ...$attributes,
      ...$dailyReward
    ];
  }
}
