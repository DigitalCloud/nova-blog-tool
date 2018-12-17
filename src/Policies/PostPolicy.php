<?php

namespace DigitalCloud\NovaBlogTool\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use DigitalCloud\NovaBlogTool\Models\Post;

class PostPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Post $post)
    {
        return $user->can('view post');
    }

    public function create(User $user)
    {
        return $user->can('create post');
    }

    public function update(User $user, Post $post)
    {
        return $user->can('update post');
    }

    public function delete(User $user, Post $post)
    {
        return $user->can('delete post');
    }

    public function restore(User $user, Post $post)
    {
        return $user->can('restore post');
    }

    public function forceDelete(User $user, Post $post)
    {
        return $user->can('force delete post');
    }


}
