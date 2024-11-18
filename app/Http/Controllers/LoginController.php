<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginController extends Controller
{
    public function register(Request $request, LoginService $loginService): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
            'nickname' => 'required',
            'is_email_enabled' => 'required',
        ]);

        $newUser = $loginService->createNewUser($request);
        $token = $loginService->createToken($newUser, $request->device_name);

        // Send verification email
        $loginService->sendVerificationEmail($newUser);
        $loginService->createBasicPlayerAttributes($newUser);

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
                'message' => 'The provided credentials are incorrect.'
            ]);
        }

        $tokenInfo = $user->createToken($request->device_name)->plainTextToken;
        $tokenData = explode("|", $tokenInfo);
        $token = $tokenData[1];

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
