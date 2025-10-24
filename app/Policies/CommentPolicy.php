<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function delete(User $user, Comment $comment)
    {
        if ($user->id === $comment->user_id) {
            return true;
        }

        return $comment->post && $comment->post->user_id === $user->id;
    }
}
