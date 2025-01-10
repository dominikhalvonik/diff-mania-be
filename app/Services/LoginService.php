<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\UserAttribute;
use App\Models\UserAttributeDefinition;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class LoginService
{
    /**
     * Create a new user
     *
     * @return User
     */
    public function createNewUser(Request $request)
    {
        Log::info('Creating new user');
        $hashedPassword = Hash::make($request->password);

        DB::beginTransaction();
        try {
            $newUser = new User;
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
            // log error to laravel log and throw error to client
            Log::error($e->getMessage());

            if ($e->getCode() == 23000) {
                throw new \Exception('User already exists');
            }

            throw new \Exception('Error creating user');
        }

        return $newUser;
    }

    /**
     * Create a user token
     */
    public function createToken(User $user, string $deviceName): string
    {
        $tokenInfo = $user->createToken($deviceName)->plainTextToken;
        $tokenData = explode('|', $tokenInfo);

        return $tokenData[1];
    }

    /**
     * Send verification email
     */
    public function sendVerificationEmail(User $newUser): void
    {
        // Use VerifyEmail mailable
        Mail::to($newUser)->send(new VerifyEmail($newUser));
    }

    /**
     * Create basic user attributes based on the attribute definitions with default values
     */
    public function createBasicUserAttributes(User $newUser): void
    {
        $attributeDefinitions = UserAttributeDefinition::all();

        foreach ($attributeDefinitions as $definition) {
            $newAttribute = new UserAttribute;
            $newAttribute->user_id = $newUser->id;
            $newAttribute->user_attribute_definition_id = $definition->id;
            $newAttribute->value = $definition->default_value;
            $newAttribute->save();
        }
    }

    public function createUserSettings(User $newUser): void
    {
        $settings = Setting::all();

        foreach ($settings as $setting) {
            $newSetting = new UserSetting;
            $newSetting->user_id = $newUser->id;
            $newSetting->setting_id = $setting->id;
            $newSetting->value = 5;
            $newSetting->save();
        }
    }
}
