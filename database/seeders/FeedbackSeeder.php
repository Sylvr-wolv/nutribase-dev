<?php

namespace Database\Seeders;

use App\Models\Distribusi;
use App\Models\Feedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil distribusi yang statusnya 'diterima'
        $distribusis = Distribusi::where('status', 'diterima')->get();

        foreach ($distribusis->take(15) as $dist) {
            Feedback::create([
                'distribusi_id' => $dist->id,
                'penerima_id' => $dist->penerima_id,
                'rating' => rand(3, 5),
                'isi_ulasan' => "Ulasan untuk distribusi nomor " . $dist->id . ". Sangat bermanfaat!",
            ]);
        }
    }
}