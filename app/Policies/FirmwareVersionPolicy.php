<?php

namespace App\Policies;

use App\Models\FirmwareVersion;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FirmwareVersionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Svi autentifikovani korisnici mogu da vide verzije
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FirmwareVersion $firmwareVersion): bool
    {
        // Admin vidi sve, ostali samo verzije projekata kojima imaju pristup
        if ($user->isAdmin()) {
            return true;
        }

        return $user->projects()->where('projects.id', $firmwareVersion->project_id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isEngineer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FirmwareVersion $firmwareVersion): bool
    {
        return $user->isEngineer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FirmwareVersion $firmwareVersion): bool
    {
        return $user->isEngineer() || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FirmwareVersion $firmwareVersion): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FirmwareVersion $firmwareVersion): bool
    {
        return false;
    }
}
