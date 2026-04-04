<?php

namespace App\Http\Controllers;

use App\Models\Penerima;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class PenerimaController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Penerima::class);

        $query = Penerima::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('rt', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $penerima = $query->paginate(10)->withQueryString();

        // $stats = [
        //     'total'         => Penerima::count(),
        //     'ibu_hamil'     => Penerima::where('kategori', 'ibu_hamil')->count(),
        //     'ibu_menyusui'  => Penerima::where('kategori', 'ibu_menyusui')->count(),
        //     'balita'        => Penerima::where('kategori', 'balita')->count(),
        //     'lainnya'       => Penerima::where('kategori', 'lainnya')->count(),
        // ];

        return view('penerima.index', compact('penerima')); //,'stats'));
    }

    public function create()
    {
        $this->authorize('create', Penerima::class);
        // tidak dipakai — modal langsung dari index
        return redirect()->route('penerima.index');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Penerima::class);

        $validated = $request->validate([
            'name'               => ['required', 'string', 'max:100'],
            'nik'                => ['required', 'string', 'size:16', 'unique:penerima,nik'],
            'no_telepon'         => ['nullable', 'string', 'max:15'],
            'alamat'             => ['required', 'string'],
            'rt'                 => ['required', 'string', 'max:10'],
            'kategori'           => ['required', Rule::in(['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya'])],
            'deskripsi_kategori' => ['nullable', 'string'],
            'estimasi_durasi'    => ['required', 'date'],
        ]);

        if ($validated['kategori'] === 'lainnya' && blank($validated['deskripsi_kategori'] ?? null)) {
            return back()
                ->withErrors(['deskripsi_kategori' => 'Wajib diisi untuk kategori Lainnya.'])
                ->withInput()
                ->with('open_modal', 'create');
        }

        $base = Str::slug($validated['name'], '.');
        $username = $base;
        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $base . $i++;
        }

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $username,
            'password' => bcrypt($validated['nik']),
            'role'     => 'penerima',
        ]);

        Penerima::create([
            'user_id'            => $user->id,
            'nik'                => $validated['nik'],
            'no_telepon'         => $validated['no_telepon'] ?? null,
            'alamat'             => $validated['alamat'],
            'rt'                 => $validated['rt'],
            'kategori'           => $validated['kategori'],
            'deskripsi_kategori' => $validated['deskripsi_kategori'] ?? null,
            'estimasi_durasi'    => $validated['estimasi_durasi'],
        ]);

        return redirect()->route('penerima.index')
            ->with('success', "Penerima {$user->name} ditambahkan. Username: {$username}");
    }

    public function show(Penerima $penerima)
    {
        $this->authorize('view', $penerima);
        $penerima->load(['user', 'distribusis.menu', 'feedbacks']);
        return view('penerima.show', compact('penerima'));
    }

    public function edit(Penerima $penerima)
    {
        $this->authorize('update', $penerima);
        // tidak dipakai — modal langsung dari index via data-* attributes
        return redirect()->route('penerima.index');
    }

    public function update(Request $request, Penerima $penerima)
    {
        $this->authorize('update', $penerima);

        $validated = $request->validate([
            'name'               => ['sometimes', 'string', 'max:100'],
            'nik'                => ['sometimes', 'string', 'size:16', Rule::unique('penerima', 'nik')->ignore($penerima->id)],
            'no_telepon'         => ['nullable', 'string', 'max:15'],
            'alamat'             => ['sometimes', 'string'],
            'rt'                 => ['sometimes', 'string', 'max:10'],
            'kategori'           => ['sometimes', Rule::in(['ibu_hamil', 'ibu_menyusui', 'balita', 'lainnya'])],
            'deskripsi_kategori' => ['nullable', 'string'],
            'estimasi_durasi'    => ['sometimes', 'date'],
        ]);

        $kategori  = $validated['kategori'] ?? $penerima->kategori;
        $deskripsi = $validated['deskripsi_kategori'] ?? $penerima->deskripsi_kategori;

        if ($kategori === 'lainnya' && blank($deskripsi)) {
            return back()
                ->withErrors(['deskripsi_kategori' => 'Wajib diisi untuk kategori Lainnya.'])
                ->withInput()
                ->with('open_modal_edit', $penerima->id);
        }

        if (isset($validated['name'])) {
            $penerima->user->update(['name' => $validated['name']]);
            unset($validated['name']);
        }

        $penerima->update($validated);

        return redirect()->route('penerima.index')
            ->with('success', 'Data penerima berhasil diperbarui.');
    }

    public function destroy(Penerima $penerima)
    {
        $this->authorize('delete', $penerima);
        $penerima->delete();
        return redirect()->route('penerima.index')
            ->with('success', 'Penerima berhasil dihapus.');
    }
}