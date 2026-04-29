<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

     User::create([
            'name' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin@example.com',
            'mobile' => '9999999999',
            'is_admin' => 1, // ✅ admin
            'user_code' => 'ADM001',
            'password' => Hash::make('12345678'),
        ]);

        // 👤 Normal User
        User::create([
            'name' => 'Test',
            'lastname' => 'User',
            'email' => 'user@example.com',
            'mobile' => '8888888888',
            'is_admin' => 0, // ✅ normal user
            'user_code' => 'USR001',
            'password' => Hash::make('12345678'),
        ]);

    }
}
