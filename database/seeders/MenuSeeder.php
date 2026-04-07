<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $kaders = User::where('role', 'kader')->pluck('id');

        for ($i = 1; $i <= 15; $i++) {
            Menu::create([
                'kader_id' => $kaders->random(),
                'nama_menu' => "Menu Gizi Seimbang $i",
                'deskripsi' => "Deskripsi untuk menu gizi ke-$i yang mengandung protein tinggi.",
                'stok' => rand(20, 100),
            ]);
        }
    }
}