<?php

namespace App\Policies;

use App\Models\Distribusi;
use App\Models\User;

class DistribusiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator() || $user->isPenerima();
    }

    public function view(User $user, Distribusi $distribusi): bool
    {
        if ($user->isKader() || $user->isKoordinator()) {
            return true;
        }

        return $user->isPenerima() && optional($distribusi->penerima)->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isKader();
    }

    public function update(User $user, Distribusi $distribusi): bool
    {
        return $user->isKader();
    }

    public function delete(User $user, Distribusi $distribusi): bool
    {
        return $user->isKader();
    }
}
