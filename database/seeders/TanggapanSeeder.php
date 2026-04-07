<?php

namespace Database\Seeders;

use App\Models\Feedback;
use App\Models\User;
use App\Models\Tanggapan;
use Illuminate\Database\Seeder;

class TanggapanSeeder extends Seeder
{
    public function run(): void
    {
        $feedbacks = Feedback::all();
        $adminOrKader = User::whereIn('role', ['koordinator', 'kader'])->pluck('id');

        foreach ($feedbacks->take(15) as $fb) {
            Tanggapan::create([
                'feedback_id' => $fb->id,
                'user_id' => $adminOrKader->random(),
                'isi_tanggapan' => "Terima kasih atas masukannya, kami akan terus meningkatkan layanan.",
            ]);
        }
    }
}