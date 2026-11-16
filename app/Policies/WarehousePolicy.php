<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Models\User;

class WarehousePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_STAFF);
    }

    public function update(User $user, Warehouse $warehouse): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasRole(User::ROLE_STAFF) && $warehouse->user_id === $user->id;
    }

    public function delete(User $user, Warehouse $warehouse): bool
    {
        return $user->isAdmin();
    }
}