<?php

namespace DigitalCloud\NovaBlogTool\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use DigitalCloud\NovaBlogTool\Models\Comment;

class CommentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Comment $comment)
    {
        return $user->can('view comment');
    }

    public function create(User $user)
    {
        return $user->can('create comment');
    }

    public function update(User $user, Comment $comment)
    {
        return $user->can('update comment');
    }

    public function delete(User $user, Comment $comment)
    {
        return $user->can('delete comment');
    }

    public function restore(User $user, Comment $comment)
    {
        return $user->can('restore comment');
    }

    public function forceDelete(User $user, Comment $comment)
    {
        return $user->can('force delete comment');
    }


}
