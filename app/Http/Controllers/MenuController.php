<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Menu::class);

        return response()->json(Menu::with('kader')->latest()->paginate(10));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Menu::class);

        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['required', 'integer', 'min:0'],
        ]);

        $validated['kader_id'] = $request->user()->id;
        $menu = Menu::create($validated);

        return response()->json($menu, 201);
    }

    public function show(Menu $menu)
    {
        $this->authorize('view', $menu);

        return response()->json($menu->load('kader'));
    }

    public function update(Request $request, Menu $menu)
    {
        $this->authorize('update', $menu);

        $validated = $request->validate([
            'nama_menu' => ['sometimes', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'stok' => ['sometimes', 'integer', 'min:0'],
        ]);

        $menu->update($validated);

        return response()->json($menu->fresh());
    }

    public function destroy(Menu $menu)
    {
        $this->authorize('delete', $menu);

        $menu->delete();

        return response()->json(['message' => 'Menu deleted']);
    }
}
