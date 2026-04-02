<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            // Koordinator
            [
                'name' => 'Koordinator Utama',
                'username' => 'koordinator',
                'email' => 'koordinator@example.com',
                'password' => Hash::make('password'),
                'role' => 'koordinator',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Kader
            [
                'name' => 'Kader Satu',
                'username' => 'kader1',
                'email' => 'kader1@example.com',
                'password' => Hash::make('password'),
                'role' => 'kader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kader Dua',
                'username' => 'kader2',
                'email' => 'kader2@example.com',
                'password' => Hash::make('password'),
                'role' => 'kader',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Penerima
            [
                'name' => 'Penerima Satu',
                'username' => 'penerima1',
                'email' => 'penerima1@example.com',
                'password' => Hash::make('password'),
                'role' => 'penerima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Penerima Dua',
                'username' => 'penerima2',
                'email' => 'penerima2@example.com',
                'password' => Hash::make('password'),
                'role' => 'penerima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}