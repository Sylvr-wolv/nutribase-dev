<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    // public function index(Request $request)
    // {
    //     // Must be penerima with a profile
    //     $penerima = $request->user()->penerimaProfile;

    //     abort_if(! $penerima, 403);

    //     $query = Distribusi::with(['menu', 'kader', 'jadwal', 'feedback'])
    //         ->where('penerima_id', $penerima->id)
    //         ->latest('waktu_distribusi');

    //     if ($search = $request->input('search')) {
    //         $query->whereHas('menu', fn($q) => $q->where('nama_menu', 'like', "%{$search}%"));
    //     }

    //     if ($status = $request->input('status')) {
    //         $query->where('status', $status);
    //     }

    //     if ($from = $request->input('date_from')) {
    //         $query->whereDate('waktu_distribusi', '>=', $from);
    //     }

    //     if ($to = $request->input('date_to')) {
    //         $query->whereDate('waktu_distribusi', '<=', $to);
    //     }

    //     $riwayat = $query->paginate(10)->withQueryString();

    //     // Unfiltered stats for this penerima
    //     $base = Distribusi::where('penerima_id', $penerima->id);
    //     $stats = [
    //         'total'    => $base->count(),
    //         'diterima' => (clone $base)->where('status', 'diterima')->count(),
    //         'gagal'    => (clone $base)->where('status', 'gagal')->count(),
    //         'pending'  => (clone $base)->where('status', 'pending')->count(),
    //     ];

    //     return view('riwayat.index', compact('riwayat', 'stats'));
    // }

    public function index(Request $request)
    {
        $user = $request->user();

        // 1. Validasi cukup berdasarkan role di tabel users
        abort_if($user->role !== 'penerima', 403, 'Akses khusus penerima.');

        // 2. Query langsung menggunakan user_id
        // Pastikan di tabel 'distribusi', kolom yang menyimpan ID user adalah 'penerima_id'
        $query = Distribusi::with(['menu', 'kader', 'jadwal', 'feedback'])
            ->where('penerima_id', $user->id) 
            ->latest('waktu_distribusi');

        // ... (Logika filter search & date tetap sama)

        $riwayat = $query->paginate(10)->withQueryString();

        // 3. Stats juga menggunakan $user->id
        $base = Distribusi::where('penerima_id', $user->id);
        $stats = [
            'total'    => $base->count(),
            'diterima' => (clone $base)->where('status', 'diterima')->count(),
            'gagal'    => (clone $base)->where('status', 'gagal')->count(),
            'pending'  => (clone $base)->where('status', 'pending')->count(),
        ];

        return view('riwayat.index', compact('riwayat', 'stats'));
    }
}