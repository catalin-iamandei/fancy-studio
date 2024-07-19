<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Location;

class LocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_location');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Location $model): bool
    {
        return $user->can('view_location');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_location');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Location $model): bool
    {
        return $user->can('update_location');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Location $model): bool
    {
        return $user->can('delete_location');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Location $model): bool
    {
        return $user->can('delete_location');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Location $model): bool
    {
        return $user->can('delete_location');
    }
}
