<?php

namespace App\Policies;

use App\Models\Podcast;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
{
    return $user->role === 'admin';
}


    /**
     * Determine whether the user can view the model.
     */
   

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role === 'admin' ;
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $host)
{
    return $user->role === 'admin';

}
    public function delete(User $user, User $host)
{
    return $user->role === 'admin';
}

    /**
     * Determine whether the user can delete the model.
     */
    

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Podcast $podcast): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Podcast $podcast): bool
    // {
    //     //
    // }
}
