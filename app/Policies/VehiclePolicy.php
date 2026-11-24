<?php

namespace App\Policies;

use App\Models\Vehicle;
use App\Models\User;

class VehiclePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(User::ROLE_ADMIN, User::ROLE_STAFF);
    }

    public function update(User $user, Vehicle $vehicle): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->hasRole(User::ROLE_STAFF) && $vehicle->user_id === $user->id;
    }

    public function delete(User $user, Vehicle $vehicle): bool
    {
        return $user->isAdmin();
    }
}