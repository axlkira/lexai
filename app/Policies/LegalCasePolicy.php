<?php

namespace App\Policies;

use App\Models\LegalCase;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LegalCasePolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Cualquier usuario autenticado puede ver su lista de casos
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LegalCase  $legalCase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, LegalCase $legalCase)
    {
        // Solo el abogado dueño del caso puede verlo
        return $user->id === $legalCase->user_id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Cualquier usuario autenticado puede crear casos
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LegalCase  $legalCase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, LegalCase $legalCase)
    {
        // Solo el abogado dueño del caso puede actualizarlo
        return $user->id === $legalCase->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LegalCase  $legalCase
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, LegalCase $legalCase)
    {
        // Solo el abogado dueño del caso puede eliminarlo
        return $user->id === $legalCase->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LegalCase $legalCase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LegalCase $legalCase): bool
    {
        return false;
    }
}
