<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;

class BoardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Workspace $board)
    {
        return $board->workspaceId === $user->id ||
            $board->workspaceId()->where('id', $user->id)->exists();
    }
}
