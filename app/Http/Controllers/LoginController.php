<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
            'nickname' => 'required',
            'is_email_enabled' => 'required',
        ]);

        $hashedPassword = Hash::make($request->password);

        $newUser = new User();
        $newUser->email = $request->email;
        $newUser->password = $hashedPassword;
        $newUser->name = $request->device_name;
        $newUser->nickname = $request->nickname;
        $newUser->is_email_enabled = $request->is_email_enabled;
        $newUser->created_at = date('Y-m-d H:i:s', time());
        $newUser->updated_at = date('Y-m-d H:i:s', time());
        $newUser->save();

        $tokenInfo = $newUser->createToken($request->device_name)->plainTextToken;
        $tokenData = explode("|", $tokenInfo);
        $token = $tokenData[1];

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

        if (! $user || ! Hash::check($request->password, $user->password)) {
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

    public function test(Request $request): JsonResponse
    {
        return response()->json([
            $request->user()
        ]);
    }
}
