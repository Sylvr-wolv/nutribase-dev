<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use App\Models\Jadwal;
use Illuminate\Database\Seeder;

class JadwalSeeder extends Seeder
{
    public function run(): void
    {
        $kaders = User::where('role', 'kader')->pluck('id');
        $menus = Menu::pluck('id');

        for ($i = 1; $i <= 15; $i++) {
            Jadwal::create([
                'kader_id' => $kaders->random(),
                'menu_id' => $menus->random(),
                'tanggal' => now()->addDays($i),
                'rt' => '00' . rand(1, 9),
                'keterangan' => "Agenda pembagian makanan rutin ke-$i",
            ]);
        }
    }
}