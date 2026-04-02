<?php

namespace App\Policies;

use App\Models\Jadwal;
use App\Models\User;

class JadwalPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator();
    }

    public function view(User $user, Jadwal $jadwal): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isKader();
    }

    public function update(User $user, Jadwal $jadwal): bool
    {
        return $user->isKader();
    }

    public function delete(User $user, Jadwal $jadwal): bool
    {
        return $user->isKader();
    }
}
