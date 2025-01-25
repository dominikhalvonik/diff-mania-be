<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\LogTable;
use Illuminate\Validation\ValidationException;
use App\Models\Ban;

class LoginController extends Controller
{
    /**
     * Costructor with dependency injection of LoginService
     */
    public function __construct(private LoginService $loginService) {}

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
            'nickname' => 'required',
            'is_email_enabled' => 'required',
        ]);

        $newUser = $this->loginService->createNewUser($request);
        $token = $this->loginService->createToken($newUser, $request->device_name);

        // $this->loginService->sendVerificationEmail($newUser);
        $this->loginService->createBasicUserAttributes($newUser);
        $this->loginService->createUserSettings($newUser);

        LogTable::create([
            'user_id' => $newUser->id,
            'log_info' => 'registered'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'token' => $token,
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Ban::where('user_id', $user->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'User is banned.',
                'reason' => Ban::where('user_id', $user->id)->first()->reason,
            ]);
        }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.',
            ]);
        }

        $token = $this->loginService->createToken($user, $request->device_name);

        LogTable::create([
            'user_id' => $user->id,
            'log_info' => 'logged in'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully',
            'token' => $token,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logged out successfully',
        ]);
    }
}
