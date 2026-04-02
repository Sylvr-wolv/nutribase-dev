<?php

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;

class FeedbackPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isKader() || $user->isKoordinator() || $user->isPenerima();
    }

    public function view(User $user, Feedback $feedback): bool
    {
        if ($user->isKader() || $user->isKoordinator()) {
            return true;
        }

        return $user->isPenerima() && optional($feedback->penerima)->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->isPenerima();
    }

    public function update(User $user, Feedback $feedback): bool
    {
        return $user->isPenerima() && optional($feedback->penerima)->user_id === $user->id;
    }

    public function delete(User $user, Feedback $feedback): bool
    {
        return $user->isPenerima() && optional($feedback->penerima)->user_id === $user->id;
    }
}
