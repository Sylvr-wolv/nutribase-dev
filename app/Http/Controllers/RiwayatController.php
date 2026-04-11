<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_if($user->role !== 'penerima', 403, 'Akses khusus penerima.');

        // Get the penerima profile first
        $penerima = $user->penerimaProfile;

        abort_if(!$penerima, 404, 'Profil penerima tidak ditemukan.');

        $query = Distribusi::with(['menu', 'kader', 'jadwal', 'feedback'])
            ->where('penerima_id', $penerima->id) // ← use penerima profile id, not user id
            ->latest('waktu_distribusi');

        if ($search = $request->input('search')) {
            $query->whereHas('menu', fn($q) => $q->where('nama', 'like', "%{$search}%"));
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('date_from')) {
            $query->whereDate('waktu_distribusi', '>=', $from);
        }

        if ($to = $request->input('date_to')) {
            $query->whereDate('waktu_distribusi', '<=', $to);
        }

        $riwayat = $query->paginate(10)->withQueryString();

        $base = Distribusi::where('penerima_id', $penerima->id); // ← same fix
        $stats = [
            'total'    => $base->count(),
            'diterima' => (clone $base)->where('status', 'diterima')->count(),
            'gagal'    => (clone $base)->where('status', 'gagal')->count(),
            'pending'  => (clone $base)->where('status', 'pending')->count(),
        ];

        return view('riwayat.index', compact('riwayat', 'stats'));
    }
}