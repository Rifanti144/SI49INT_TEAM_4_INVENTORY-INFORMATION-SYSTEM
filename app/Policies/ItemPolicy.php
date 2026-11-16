<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_STAFF);
    }

    public function update(User $user, Item $item): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasRole(User::ROLE_STAFF) && $item->user_id === $user->id;
    }

    public function delete(User $user, Item $item): bool
    {
        return $user->isAdmin();
    }
}