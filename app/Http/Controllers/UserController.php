<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function editProfile(Request $request)
    {
        return view('profile.index', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'username'         => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password'     => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $updates = [
            'name'     => $validated['name'],
            'username' => $validated['username'],
        ];

        if (! blank($validated['new_password'] ?? null)) {
            $updates['password'] = $validated['new_password'];
        }

        $user->update($updates);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            $query->where('role', $role);
        }

        $users = $query->paginate(10)->withQueryString();

        $stats = [
            'total'       => User::count(),
            'penerima'    => User::where('role', 'penerima')->count(),
            'kader'       => User::where('role', 'kader')->count(),
            'koordinator' => User::where('role', 'koordinator')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['kader', 'koordinator', 'penerima'])],
        ]);

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['kader', 'koordinator', 'penerima'])],
        ]);

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}