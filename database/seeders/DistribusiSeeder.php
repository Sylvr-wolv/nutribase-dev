<?php

namespace Database\Seeders;

use App\Models\Jadwal;
use App\Models\Penerima;
use App\Models\Menu;
use App\Models\User;
use App\Models\Distribusi;
use Illuminate\Database\Seeder;

class DistribusiSeeder extends Seeder
{
    public function run(): void
    {
        $penerimas = Penerima::pluck('id');
        $kaders = User::where('role', 'kader')->pluck('id');
        $menus = Menu::pluck('id');
        $jadwals = Jadwal::pluck('id');

        for ($i = 1; $i <= 15; $i++) {
            Distribusi::create([
                'jadwal_id' => $jadwals->random(),
                'penerima_id' => $penerimas->random(),
                'menu_id' => $menus->random(),
                'kader_id' => $kaders->random(),
                'waktu_distribusi' => now()->subHours(rand(1, 24)),
                'status' => rand(0, 1) ? 'diterima' : 'pending',
                'keterangan' => 'Proses distribusi berjalan lancar.',
            ]);
        }
    }
}