<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // فرم ساخت پروژه (اگر از Inertia استفاده می‌کنی، می‌تونی از این هم استفاده کنی)
    public function create()
    {
        return inertia('tasks/Create');
    }
    // ۱) ساخت تسک شخصی (مختص یک فرد)
    public function storePersonal(Request $request)
    {
        $user = $request->user();

        $allowedEstimations = [1, 3, 5, 8, 13];

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimation'  => ['nullable', 'integer', Rule::in($allowedEstimations)],
        ]);

        Task::create([
            'project_id'    => null,          // شخصی
            'user_id'       => $user->id,     // صاحب تسک
            'created_by_id' => $user->id,     // سازنده تسک
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'estimation'    => $data['estimation'] ?? null,
            'status'        => 'todo',
        ]);

        return redirect()->route('dashboard');
    }

    // ۲) ساخت تسک برای یک پروژه
    public function storeForProject(Request $request, Project $project)
    {
        $user = $request->user();

        // فقط اعضای پروژه اجازه ساخت تسک دارند
        if (! $project->users->contains($user->id)) {
            abort(403);
        }

        $allowedEstimations = [1, 3, 5, 8, 13];

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimation'  => ['required', 'integer', Rule::in($allowedEstimations)],
        ]);

        Task::create([
            'project_id'    => $project->id,  // تسک پروژه‌ای
            'user_id'       => null,          // تا انجام نشده، به فرد خاصی نسبت داده نمی‌شود
            'created_by_id' => $user->id,     // هر کسی ساخته را نگه می‌داریم
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'estimation'    => $data['estimation'],
            'status'        => 'todo',
        ]);

        return redirect()->route('dashboard');
    }

    // ۳) انجام دادن تسک پروژه‌ای و نسبت دادن به فرد
    public function markDone(Request $request, Task $task)
    {
        $user = $request->user();

        // باید تسک پروژه‌ای باشد (نه شخصی)
        if (is_null($task->project_id)) {
            abort(400, 'این تسک پروژه‌ای نیست.');
        }

        // کاربر باید عضو پروژه باشد
        if (! $task->project->users->contains($user->id)) {
            abort(403);
        }

        if ($task->status === 'done') {
            return redirect()->route('dashboard');
        }

        $task->update([
            'status'  => 'done',
            'done_at' => now(),
            'user_id' => $user->id, // اینجا تسک به کسی که انجامش داده نسبت داده می‌شود
        ]);

        return redirect()->route('dashboard');
    }
    
}
