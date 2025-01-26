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
            User::create([
                'email' => $email,
                'password' => Hash::make($password),
                'is_admin' => 1,
                'name' => 'Admin User - ' . $email,
                'nickname' => 'Admin ' . rand(1000, 9999),
                'is_email_enabled' => true,
            ]);
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
