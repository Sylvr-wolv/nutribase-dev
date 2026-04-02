<?php

namespace App\Policies;

use App\Models\Tanggapan;
use App\Models\User;

class TanggapanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator() || $user->isPenerima();
    }

    public function view(User $user, Tanggapan $tanggapan): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator();
    }

    public function update(User $user, Tanggapan $tanggapan): bool
    {
        return ($user->isKader() || $user->isKoordinator()) && $tanggapan->user_id === $user->id;
    }

    public function delete(User $user, Tanggapan $tanggapan): bool
    {
        return ($user->isKader() || $user->isKoordinator()) && $tanggapan->user_id === $user->id;
    }
}
