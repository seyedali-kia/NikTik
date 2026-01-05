<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;


    public function view(User $user, Task $task)
    {

        if ($task->project_id === null) {
            return $user->id === $task->created_by_id;
        }else {
            return $task->project->users()->whereKey($user->id)->exists();
        }
    }


    public function update(User $user, Task $task)
    {
        
        if ($user->id === $task->created_by_id) {
            return true;
        }

        if ($task->project_id === null) {
            return false;
        }

        return $user->id === $task->project->owner_id;
    }

    public function create(User $user, ?int $projectId = null)
    {
        if ($projectId === null) {
            return true;
        }

        return $user->involvedProjects()->whereKey($projectId)->exists();
    }

    public function markAsCompleted(User $user, Task $task)
    {

        if ($task->project_id === null) {
            return $user->id === $task->created_by_id;
        }else {
            return $task->project->users()->whereKey($user->id)->exists();
        }
    }


}
