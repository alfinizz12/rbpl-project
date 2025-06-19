<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

class AttachmentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Attachment $attachment)
    {
        return $user->id === $attachment->userId;
    }

    public function delete(User $user, Attachment $attachment)
    {
        return $user->id === $attachment->userId;
    }

}
