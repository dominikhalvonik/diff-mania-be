<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // It should return a response like this:
    //     {
    //         "status": "success", 
    //         "message": "User data retrieved successfuly.",
    //         //LOAD USER DATA SA SPUSTI UPLNE NA ZACIATKU, KED SA SPUSTI HRA NA NACITANIE UDAJOV O HRACOVI  
    //         "data": {
    //             "name": "petko", //
    //             "coins": 69,
    //             "lives": 2,
    //             "timeForRefillLife": 200, 
    //             "experience": 23, //Skusenosti 
    //             // "experienceToNextLevel": 69, //toto by nemuselo byt pri tomto api calle, mozno bude lepsie ak si nataham cely config co sa tyka experience/level/reward za level up 
    //             "level": 1,

    //             "currentWins": 2, //toto je kolko ma vyhier do dalsieho darceku
    //             "winsNeededToNextGift": 6, //toto neviem ci je staticke, že kazdych 6 winov otvaras darceky alebo sa to stupnuje

    //             "tournamentWins": 420, //neviem co su tournamenty, su to celkove výhry?
    //             "tournamentLoses": 69,


    //             "selectedPFP": 2,  //obrazok profilu, asi idealne budu IDcka(int)
    //             "unlockedPFP": [1,2,3,69], //pole odomknutych

    //             "boostAddTime": 2, //kolko ma boostov na pridanie casu
    //             "boostHint": 2, //kolko ma boostov na napovedu

    //             "hasFreeNickName" : true, //prva zmena nicku je zadarmenko
    //             "hasADsRemoved" : false, //v premiume sa da kupit 
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
    //                 "tookRewardForConnectingAccount":{//ci si vybral task za registraciu/prepojenie uctu, ak nie bude zobrazeny task
    //                     "progress": 0,
    //                     "needToComplete": 1,
    //                     "reward": 100 //toto bude vzdy coins
    //                 } 
    //             }],
    //             "dailyRewards":[
    //                 {
    //                     "day": 1,
    //                     "reward": 1,
    //                     "opened": true // ak je true tak sa neda otvorit, sluzi iba na ukazanie historie daily rewardov
    //                 },
    //                 {
    //                     "day": 2,
    //                     "reward": 2,
    //                     "opened": false // tento sa da otvorit
    //                 }
    //             ],

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

    //     }
    public function loadUserData(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is authenticated
        if ($user) {
            // Return every information from user and its attributes from db
            return response()->json([
                'status' => 'success',
                'message' => 'User data retrieved successfuly.',
                'data' => $user
            ], 200);
        } else {
            // Return an unauthorized response
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }
    }
}
