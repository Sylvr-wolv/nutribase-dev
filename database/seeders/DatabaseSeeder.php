<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // 1. Buat User (Koordinator, Kader, Penerima)
            PenerimaSeeder::class,  // 2. Buat profil detail Penerima (Butuh user_id)
            MenuSeeder::class,      // 3. Buat Menu (Butuh kader_id)
            JadwalSeeder::class,    // 4. Buat Jadwal (Butuh menu_id & kader_id)
            DistribusiSeeder::class,// 5. Buat Distribusi (Butuh jadwal, penerima, menu, kader)
            FeedbackSeeder::class,  // 6. Buat Feedback (Butuh distribusi_id)
            TanggapanSeeder::class, // 7. Buat Tanggapan (Butuh feedback_id & user_id)
        ]);
    }
}