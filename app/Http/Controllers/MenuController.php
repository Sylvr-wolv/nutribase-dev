<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Menu::class);

        $query = Menu::with('kader')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_menu', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $menu = $query->paginate(12)->withQueryString();

        $stats = [
            'total'        => Menu::count(),
            'stok_total'   => Menu::sum('stok'),
            'stok_habis'   => Menu::where('stok', 0)->count(),
            'stok_sedikit' => Menu::where('stok', '>', 0)->where('stok', '<=', 10)->count(),
        ];

        return view('menu.index', compact('menu', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Menu::class);
        return redirect()->route('menu.index');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Menu::class);

        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'stok'      => ['required', 'integer', 'min:0'],
            'gambar'    => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['kader_id'] = $request->user()->id;

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($validated);

        return redirect()->route('menu.index')
            ->with('success', "Menu \"{$validated['nama_menu']}\" berhasil ditambahkan.");
    }

    public function show(Menu $menu)
    {
        $this->authorize('view', $menu);
        $menu->load(['kader', 'jadwals', 'distribusis']);
        return view('menu.show', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        $this->authorize('update', $menu);
        return redirect()->route('menu.index');
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $validated = $request->validate([
            'nama_menu'    => ['sometimes', 'string', 'max:150'],
            'deskripsi'    => ['nullable', 'string'],
            'stok'         => ['sometimes', 'integer', 'min:0'],
            'gambar'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ]);

        // Delete old image if new one uploaded or checkbox checked
        if ($request->hasFile('gambar') || $request->boolean('hapus_gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $validated['gambar'] = null;
        }

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        unset($validated['hapus_gambar']);
        $menu->update($validated);

        return redirect()->route('menu.index')
            ->with('success', "Menu \"{$menu->nama_menu}\" berhasil diperbarui.");
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $nama = $menu->nama_menu;
        $menu->delete();

        return redirect()->route('menu.index')
            ->with('success', "Menu \"{$nama}\" berhasil dihapus.");
    }
}