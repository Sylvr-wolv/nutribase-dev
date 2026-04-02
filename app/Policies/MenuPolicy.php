<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator() || $user->isPenerima();
    }

    public function view(User $user, Menu $menu): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->isKader();
    }

    public function update(User $user, Menu $menu): bool
    {
        return $user->isKader();
    }

    public function delete(User $user, Menu $menu): bool
    {
        return $user->isKader();
    }
}
