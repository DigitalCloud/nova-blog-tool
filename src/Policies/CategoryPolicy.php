<?php

namespace DigitalCloud\NovaBlogTool\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use DigitalCloud\NovaBlogTool\Models\Category;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Category $category)
    {
        return $user->can('view category');
    }

    public function create(User $user)
    {
        return $user->can('create category');
    }

    public function update(User $user, Category $category)
    {
        return $user->can('update category');
    }

    public function delete(User $user, Category $category)
    {
        return $user->can('delete category');
    }

    public function restore(User $user, Category $category)
    {
        return $user->can('restore category');
    }

    public function forceDelete(User $user, Category $category)
    {
        return $user->can('force delete category');
    }


}
