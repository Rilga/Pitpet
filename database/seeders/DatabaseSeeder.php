<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        User::create([
            'name' => 'Admin Pitpet',
            'email' => 'admin@pitpet.com', // Ganti dengan email yang Anda inginkan
            'password' => Hash::make('admin12345'), // Ganti 'password' dengan password yang aman
            'role' => 'admin',
        ]);

        // 2. Akun Groomer (User)
        User::create([
            'name' => 'Groomer Satu',
            'email' => 'groomer1@pitpet.com', 
            'password' => Hash::make('user12345'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Groomer Dua',
            'email' => 'groomer2@pitpet.test', 
            'password' => Hash::make('user12345'), 
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Groomer Tiga',
            'email' => 'groomer3@pitpet.test', 
            'password' => Hash::make('user12345'), 
            'role' => 'user',
        ]);
        
    }
}
