<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin
        User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password'), // Pastikan untuk mengganti password ini dalam produksi
            'role' => 'superadmin',
        ]);

        // Admin
        User::create([
            'username' => 'adminuser',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'), // Ganti password ini juga
            'role' => 'admin',
        ]);
    }
}
