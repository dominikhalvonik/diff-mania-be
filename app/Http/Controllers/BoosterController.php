<?php

namespace App\Http\Controllers;

use App\Services\UserBoosterService;
use Illuminate\Http\Request;
use App\Models\Booster;

class BoosterController extends Controller
{
    public function useBooster(Request $request, Booster $booster, UserBoosterService $userBoosterService)
    {
        $user = $request->user();

        $result = $userBoosterService->removeBooster($user, $booster, 1);

        if ($result) {
            return response()->json([
                'message' => 'Booster used successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'You do not have this booster',
            ], 400);
        }
    }

    public function addBooster(Request $request, Booster $booster, UserBoosterService $userBoosterService)
    {
        $user = $request->user();

        $result = $userBoosterService->addBooster($user, $booster, 1);

        if ($result) {
            return response()->json([
                'message' => 'Booster added successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Booster was not added',
            ], 400);
        }
    }
}
