<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Jadwal::class);

        return response()->json(Jadwal::with(['kader', 'menu'])->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Jadwal::class);

        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menu,id'],
            'tanggal' => ['required', 'date'],
            'rt' => ['required', 'string', 'max:10'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['kader_id'] = $request->user()->id;
        $jadwal = Jadwal::create($validated);

        return response()->json($jadwal, 201);
    }

    public function show(Jadwal $jadwal)
    {
        $this->authorize('view', $jadwal);

        return response()->json($jadwal->load(['kader', 'menu']));
    }

    public function update(Request $request, Jadwal $jadwal)
    {
        $this->authorize('update', $jadwal);

        $validated = $request->validate([
            'menu_id' => ['sometimes', 'exists:menu,id'],
            'tanggal' => ['sometimes', 'date'],
            'rt' => ['sometimes', 'string', 'max:10'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $jadwal->update($validated);

        return response()->json($jadwal->fresh());
    }

    public function destroy(Jadwal $jadwal)
    {
        $this->authorize('delete', $jadwal);

        $jadwal->delete();

        return response()->json(['message' => 'Jadwal deleted']);
    }
}
