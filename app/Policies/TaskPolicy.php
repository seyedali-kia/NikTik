<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the given task can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return bool
     */
    public function update(User $user, Task $task)
    {
        // فقط مالک یا انجام‌دهنده می‌تواند تسک را ویرایش کند
        return $user->id === $task->created_by_id || $user->id === $task->completed_by_id;
    }

    /**
     * Determine if the given task can be deleted by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return bool
     */
    public function delete(User $user, Task $task)
    {
        // فقط مالک می‌تواند تسک را حذف کند
        return $user->id === $task->created_by_id;
    }

    /**
     * Determine if the user can mark the task as completed.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Task  $task
     * @return bool
     */
    public function markAsCompleted(User $user, Task $task)
    {

        if ($task->project_id === null) {
            return $user->id === $task->created_by_id;
        }else {
            return $task->project->users()->whereKey($user->id)->exists();
        }
    }
}
