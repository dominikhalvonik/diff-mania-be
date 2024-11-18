<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\DB;
use App\Models\PlayerAttributes;
use App\Models\PlayerAttributesDefinitions;
use Illuminate\Support\Str;
use Log;

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

    DB::beginTransaction();
    try {
      $newUser = new User();
      $newUser->id = Str::uuid();
      $newUser->email = $request->email;
      $newUser->password = $hashedPassword;
      // TODO: Change this to valid seconday table save
      $newUser->name = $request->device_name;
      $newUser->nickname = $request->nickname;
      $newUser->is_email_enabled = (int) $request->is_email_enabled;
      $newUser->created_at = date('Y-m-d H:i:s', time());
      $newUser->updated_at = date('Y-m-d H:i:s', time());

      $newUser->save();
      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
      if ($e->getCode() == 23000) {
        throw new \Exception('User already exists');
      }
      // log error to laravel log and throw error to client
      Log::error($e->getMessage());

      throw new \Exception('Error creating user');
    }

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

  /**
   * Send verification email
   * @param \App\Models\User $newUser
   * @return void
   */
  public function sendVerificationEmail(User $newUser): void
  {
    // Use VerifyEmail mailable
    Mail::to($newUser)->send(new VerifyEmail());
  }

  /**
   * Create basic player attributes based on the attribute definitions with default values
   * 
   * @param User $newUser
   * @return void
   */
  public function createBasicPlayerAttributes(User $newUser): void
  {
    $attributeDefinitions = PlayerAttributesDefinitions::all();

    foreach ($attributeDefinitions as $definition) {
      $newAttribute = new PlayerAttributes();
      $newAttribute->user_id = $newUser->id;
      $newAttribute->player_attributes_definition_id = $definition->id;
      $newAttribute->value = $definition->default_value;
      $newAttribute->save();
    }
  }
}