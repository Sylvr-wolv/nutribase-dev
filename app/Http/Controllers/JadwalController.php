<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Menu;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Jadwal::class, 'jadwal');
    }

    public function index(Request $request)
    {
        $query = Jadwal::with(['kader', 'menu'])
            ->orderBy('tanggal', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rt', 'like', "%$search%")
                  ->orWhere('keterangan', 'like', "%$search%")
                  ->orWhereHas('menu', fn($q) => $q->where('nama_menu', 'like', "%$search%"))
                  ->orWhereHas('kader', fn($q) => $q->where('name', 'like', "%$search%"));
            });
        }

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->date_to);
        }

        $jadwals = $query->paginate(15)->withQueryString();
        $menus   = Menu::orderBy('nama_menu')->get();
        $rtList  = Jadwal::distinct()->orderBy('rt')->pluck('rt');

        return view('jadwal.index', compact('jadwals', 'menus', 'rtList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id'    => 'required|exists:menu,id',
            'tanggal'    => 'required|date',
            'rt'         => 'required|string|max:10',
            'keterangan' => 'nullable|string',
        ]);

        Jadwal::create([
            ...$validated,
            'kader_id' => auth()->id(),
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $validated = $request->validate([
            'menu_id'    => 'required|exists:menu,id',
            'tanggal'    => 'required|date',
            'rt'         => 'required|string|max:10',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal->update($validated);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}