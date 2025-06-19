<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user, Task $task)
    {
        return $task->users()->where('id', $user->id)->exists();
    }

    public function update(User $user, Task $task)
    {
        return $user->id === $task->board->workspace->ownerId;
    }

    public function delete(User $user, Task $task)
    {
        return $user->id === $task->board->workspace->ownerId;
    }

}
