<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if(request()->is(config('app.admin_path').'/admins/*')){
            return $user->can('tasks.list.show');
        }
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        if(request()->is(config('app.admin_path').'/admins/*')){
            return $user->can('tasks.list.show');
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if(request()->is(config('app.admin_path').'/admins/*')){
            return $user->can('tasks.store.store');
        }
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        if(request()->is(config('app.admin_path').'/admins/*')){
            return $user->can('tasks.list.edit');
        }
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        if(request()->is(config('app.admin_path').'/admins/*')){
            return $user->can('tasks.list.delete');
        }
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        //
    }
}
