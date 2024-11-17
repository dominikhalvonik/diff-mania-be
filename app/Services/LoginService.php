<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginService
{
  /**
   * Create a new user
   *
   * @param Request $request
   * @return User
   */
  public function createNewUser(Request $request)
  {
    $hashedPassword = Hash::make($request->password);

    $newUser = new User();
    $newUser->email = $request->email;
    $newUser->password = $hashedPassword;
    // TODO: Change this to valid seconday table save
    $newUser->name = $request->device_name;
    $newUser->nickname = $request->nickname;
    $newUser->is_email_enabled = (int) $request->is_email_enabled;
    $newUser->created_at = date('Y-m-d H:i:s', time());
    $newUser->updated_at = date('Y-m-d H:i:s', time());
    $newUser->save();

    return $newUser;
  }

  /**
   * Summary of createToken
   * @param \App\Models\User $user
   * @param string $deviceName
   * @return string
   */
  public function createToken(User $user, string $deviceName): string
  {
    $tokenInfo = $user->createToken($deviceName)->plainTextToken;
    $tokenData = explode("|", $tokenInfo);
    return $tokenData[1];
  }
}