<?php

namespace App\Http\Controllers;

use App\Models\Penerima;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PenerimaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Penerima::class);

        return response()->json(
            Penerima::with('user')->latest()->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $this->authorize('create', Penerima::class);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'nik' => ['required', 'string', 'size:16', 'unique:penerima,nik'],
            'no_telepon' => ['nullable', 'string', 'max:15'],
            'alamat' => ['required', 'string'],
            'rt' => ['required', 'string', 'max:10'],
            'kategori' => ['required', Rule::in(['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya'])],
            'deskripsi_kategori' => ['nullable', 'string'],
            'estimasi_durasi' => ['required', 'date'],
        ]);

        if ($validated['kategori'] === 'lainnya' && blank($validated['deskripsi_kategori'] ?? null)) {
            return response()->json(['message' => 'deskripsi_kategori wajib diisi untuk kategori lainnya'], 422);
        }

        $penerima = Penerima::create($validated);

        return response()->json($penerima, 201);
    }

    public function show(Penerima $penerima)
    {
        $this->authorize('view', $penerima);

        return response()->json($penerima->load('user'));
    }

    public function update(Request $request, Penerima $penerima)
    {
        $this->authorize('update', $penerima);

        $validated = $request->validate([
            'user_id' => ['sometimes', 'exists:users,id'],
            'nik' => ['sometimes', 'string', 'size:16', Rule::unique('penerima', 'nik')->ignore($penerima->id)],
            'no_telepon' => ['nullable', 'string', 'max:15'],
            'alamat' => ['sometimes', 'string'],
            'rt' => ['sometimes', 'string', 'max:10'],
            'kategori' => ['sometimes', Rule::in(['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya'])],
            'deskripsi_kategori' => ['nullable', 'string'],
            'estimasi_durasi' => ['sometimes', 'date'],
        ]);

        $kategori = $validated['kategori'] ?? $penerima->kategori;
        $deskripsi = $validated['deskripsi_kategori'] ?? $penerima->deskripsi_kategori;
        if ($kategori === 'lainnya' && blank($deskripsi)) {
            return response()->json(['message' => 'deskripsi_kategori wajib diisi untuk kategori lainnya'], 422);
        }

        $penerima->update($validated);

        return response()->json($penerima->fresh());
    }

    public function destroy(Penerima $penerima)
    {
        $this->authorize('delete', $penerima);

        $penerima->delete();

        return response()->json(['message' => 'Penerima deleted']);
    }
}
