<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $model): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_category');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $model): bool
    {
        return $user->can('update_category');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $model): bool
    {
        return $user->can('delete_category');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $model): bool
    {
        return $user->can('delete_category');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $model): bool
    {
        return $user->can('delete_category');
    }
}
