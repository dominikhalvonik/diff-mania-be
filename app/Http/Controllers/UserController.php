<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function loadUserData(Request $request, UserService $userService)
    {
        // Get the authenticated user
        $baseUser = User::find(Auth::id());

        if ($baseUser == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        $completeUserData = $userService->loadInitData($baseUser);

        return response()->json([
            'status' => 'success',
            'message' => 'User data retrieved successfuly.',
            'data' => $completeUserData
        ]);
    }
}
