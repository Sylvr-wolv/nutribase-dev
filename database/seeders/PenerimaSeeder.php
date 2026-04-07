<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Penerima;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PenerimaSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = User::where('role', 'penerima')->get();

        foreach ($users as $index => $user) {
            Penerima::create([
                'user_id' => $user->id,
                'nik' => $faker->unique()->numerify('3210############'), // Total 16 digit
                'no_telepon' => substr($faker->phoneNumber(), 0, 15),
                'alamat' => $faker->streetAddress(), // Pakai streetAddress agar lebih pendek dari address()
                'rt' => $faker->numerify('RT ##'), // Sesuaikan dengan limit string(10) di migrasi
                'kategori' => $faker->randomElement(['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya']),
                'deskripsi_kategori' => null, 
                'estimasi_durasi' => $faker->dateTimeBetween('now', '+1 year'),
            ]);
        }
    }
}