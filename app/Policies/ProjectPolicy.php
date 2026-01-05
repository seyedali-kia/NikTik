<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;
    /**
     * Determine if the given project can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */

    public function view(User $user, Project $project)
    {
        return $project->users()->whereKey($user->id)->exists();
    }

    public function leave(User $user, Project $project)
    {
        return $user->id !== $project->owner_id && $project->users()->whereKey($user->id)->exists();
    }

    public function update(User $user, Project $project)
    {
        return $user->id === $project->owner_id;
    }
}
