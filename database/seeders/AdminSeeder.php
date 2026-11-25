<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => config('app.admin_email', env('ADMIN_EMAIL', 'admin@example.com'))],
            [
                'name' => config('app.admin_name', env('ADMIN_NAME', 'Admin User')),
                'email' => config('app.admin_email', env('ADMIN_EMAIL', 'admin@example.com')),
                'password' => Hash::make(config('app.admin_password', env('ADMIN_PASSWORD', 'Admin@123456'))),
                'role' => 'admin'
            ]
        );
    }
}
