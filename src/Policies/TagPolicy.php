<?php

namespace DigitalCloud\NovaBlogTool\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Tags\Tag;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Tag $tag)
    {
        return $user->can('view tag');
    }

    public function create(User $user)
    {
        return $user->can('create tag');
    }

    public function update(User $user, Tag $tag)
    {
        return $user->can('update tag');
    }

    public function delete(User $user, Tag $tag)
    {
        return $user->can('delete tag');
    }

    public function restore(User $user, Tag $tag)
    {
        return $user->can('restore tag');
    }

    public function forceDelete(User $user, Tag $tag)
    {
        return $user->can('force delete tag');
    }


}
