<?php

namespace App\Policies;

use App\Models\SupportRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupportRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Svi autentifikovani korisnici mogu da vide prijave
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportRequest $supportRequest): bool
    {
        // Admin vidi sve
        if ($user->isAdmin()) {
            return true;
        }

        // Inženjeri vide prijave za projekte kojima imaju pristup
        if ($user->isEngineer()) {
            return $user->projects()
                ->where('projects.id', $supportRequest->firmwareVersion->project_id)
                ->exists();
        }

        // Klijenti vide samo svoje prijave
        return $supportRequest->created_by === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Svi autentifikovani korisnici mogu da kreiraju prijave
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupportRequest $supportRequest): bool
    {
        // Inženjeri i admin mogu da menjaju sve prijave
        if ($user->isEngineer() || $user->isAdmin()) {
            return true;
        }

        // Autor može da menja svoje prijave
        return $supportRequest->created_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupportRequest $supportRequest): bool
    {
        // Samo admin može da briše prijave
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupportRequest $supportRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupportRequest $supportRequest): bool
    {
        return false;
    }
}
