<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator();
    }

    public function view(User $user, User $model): bool
    {
        return $user->isKader() || $user->isKoordinator() || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->isKader();
    }

    public function update(User $user, User $model): bool
    {
        return $user->isKader() || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->isKader() && $user->id !== $model->id;
    }
}