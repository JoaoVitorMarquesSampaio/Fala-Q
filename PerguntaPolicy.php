<?php

namespace App\Policies;

use App\Models\Pergunta;
use App\Models\User;

class PerguntaPolicy
{
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Pergunta $pergunta): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Pergunta $pergunta): bool
    {
        return false;
    }

    // SPRINT 05 - TICKET #002
    public function delete(User $user, Pergunta $pergunta): bool
    {
        return $user->id === $pergunta->user_id
            || $user->id === $pergunta->evento->user_id;
    }

    public function restore(User $user, Pergunta $pergunta): bool
    {
        return false;
    }

    public function forceDelete(User $user, Pergunta $pergunta): bool
    {
        return false;
    }
}
