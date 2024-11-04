<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wifi;
use Illuminate\Auth\Access\HandlesAuthorization;

class WifiPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_wifi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wifi $wifi): bool
    {
        return $user->can('view_wifi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_wifi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wifi $wifi): bool
    {
        return $user->can('update_wifi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wifi $wifi): bool
    {
        return $user->can('delete_wifi');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_wifi');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Wifi $wifi): bool
    {
        return $user->can('force_delete_wifi');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_wifi');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Wifi $wifi): bool
    {
        return $user->can('restore_wifi');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_wifi');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Wifi $wifi): bool
    {
        return $user->can('replicate_wifi');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_wifi');
    }
}
