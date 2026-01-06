<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function createTask(User $user, array $data, ?int $projectId): Task
    {
        return DB::transaction(function () use ($user, $data, $projectId) {
            return Task::create([
                'project_id'      => $projectId,
                'completed_by_id' => null,
                'created_by_id'   => $user->id,
                'title'           => $data['title'],
                'description'     => $data['description'] ?? null,
                'estimation'      => $data['estimation'] ?? null,
                'status'          => $data['status'] ?? 'todo',
            ]);
        });
    }

    public function updateTask(Task $task, array $data): Task
    {
        $task->update($data);
        return $task->refresh();
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }

    public function startTask(User $user, Task $task): Task
    {
        $task->update([
            'status' => 'doing',
            'completed_by_id' => $user->id,
        ]);

        return $task->refresh();
    }

    public function completeTask(User $user, Task $task): Task
    {
        $task->update([
            'status' => 'done',
            'completed_by_id' => $user->id,
        ]);

        return $task->refresh();
    }
}