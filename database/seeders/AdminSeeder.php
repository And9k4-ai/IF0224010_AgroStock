<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@agrostock.com'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('12345678'),
            ]
        );

        // User
        User::updateOrCreate(
            ['email' => 'user@agrostock.com'],
            [
                'name' => 'User',
                'role' => 'user',
                'password' => Hash::make('12345678'),
            ]
        );
    }
}