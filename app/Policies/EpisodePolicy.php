<?php

namespace App\Policies;

use App\Models\Episode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EpisodePolicy
{
    /**
     * Determine whether the user can view any models.
     */
 

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'admin' || $user->role === 'Animateur';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Episode $episode)
    {
        return $user->role === 'admin' || ($user->role === 'Animateur' && $user->id === $episode->podcast->user_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Episode $episode)
    {
        return $user->role === 'admin' || ($user->role === 'Animateur' && $user->id === $episode->podcast->user_id);
    }

    /**
     * Determine whether the user can restore the model.
     */
   
}
