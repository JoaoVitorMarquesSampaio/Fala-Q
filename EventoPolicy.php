<?php

namespace App\Policies;

use App\Models\Evento;
use App\Models\User;

class EventoPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Evento $evento): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Evento $evento): bool
    {
        return false;
    }

    public function delete(User $user, Evento $evento): bool
    {
        return false;
    }

    public function restore(User $user, Evento $evento): bool
    {
        return false;
    }

    public function forceDelete(User $user, Evento $evento): bool
    {
        return false;
    }

    // Sprint 07 - Ticket #016
    public function moderate(User $user, Evento $evento): bool
    {
        return $user->id === $evento->user_id;
    }
}