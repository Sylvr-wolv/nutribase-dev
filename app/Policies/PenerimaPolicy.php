<?php

namespace App\Policies;

use App\Models\Penerima;
use App\Models\User;

class PenerimaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator();
    }

    public function view(User $user, Penerima $penerima): bool
    {
        return $user->isKader()
            || $user->isKoordinator()
            || ($user->isPenerima() && $penerima->user_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->isKader();
    }

    public function update(User $user, Penerima $penerima): bool
    {
        return $user->isKader();
    }

    public function delete(User $user, Penerima $penerima): bool
    {
        return $user->isKader();
    }
}
