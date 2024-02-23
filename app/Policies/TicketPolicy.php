<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can view any models.
     */
    // public function viewAny(User $user): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can view the model.
     */
    // public function view(User $user, Ticket $ticket)
    // {
    //     return $user->role_id == 1 || (auth()->check() && $ticket->pic_id == auth()->id());
    // }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user)
    // {
    //     return $user->role_id == 1;
    // }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket)
    {
        return $user->role_id == 1 || (auth()->check() && $ticket->pic_id == auth()->id());
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket)
    {
        return $user->role_id == 1 || (auth()->check() && $ticket->pic_id == auth()->id());
    }

    /**
     * Determine whether the user can restore the model.
     */
    // public function restore(User $user, Ticket $ticket): bool
    // {
    //     //
    // }

    /**
     * Determine whether the user can permanently delete the model.
     */
    // public function forceDelete(User $user, Ticket $ticket): bool
    // {
    //     //
    // }
}
