<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Typology;

class TypologyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_typology');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Typology $model): bool
    {
        return $user->can('view_typology');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_typology');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Typology $model): bool
    {
        return $user->can('update_typology');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Typology $model): bool
    {
        return $user->can('delete_typology');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Typology $model): bool
    {
        return $user->can('delete_typology');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Typology $model): bool
    {
        return $user->can('delete_typology');
    }
}
