<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'app:create-admin {email?} {password?}';
    protected $description = 'Create a new admin user';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Enter the email');
        $password = $this->argument('password') ?? $this->secret('Enter the password');

        $user = User::where('email', $email)->first();

        // create an admin user if it doesn't exist
        if (!$user) {
            $request = new \Illuminate\Http\Request([
                'email' => $email,
                'password' => $password,
                'device_name' => 'Admin User - ' . $email,
                'nickname' => 'Admin ' . rand(1000, 9999),
                'is_email_enabled' => true,
            ]);
            $user = app('App\Services\LoginService')->createNewUser($request);
            $user->is_admin = true;
            $user->save();
            app('App\Services\LoginService')->createBasicUserAttributes($user);
            app('App\Services\LoginService')->createUserSettings($user);
        } else if ($user->is_admin) {
            $this->error('Admin user already exists.');
        } else {
            $user->is_admin = true;
            $user->password = Hash::make($password);
            $user->save();
        }

        $this->info('Admin user created successfully.');
    }
}
