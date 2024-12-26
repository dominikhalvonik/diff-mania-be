<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Costructor with dependency injection of LoginService
     */
    public function __construct(private LoginService $loginService)
    {
    }

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
        $this->loginService->createBasicPlayerAttributes($newUser);
        $this->loginService->createUserSettings($newUser);

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

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect.',
            ]);
        }

        $token = $this->loginService->createToken($user, $request->device_name);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
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
