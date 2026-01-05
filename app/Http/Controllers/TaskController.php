<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TaskController extends Controller
{

    // نمایش تسک‌های شخصی کاربر
    public function index(Request $request)
    {
        $user = $request->user();

        // گرفتن تسک‌های شخصی
        $tasks = $user->personalTasks()
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Tasks', [
            'tasks' => $tasks,
        ]);
    }

    // نمایش یک تسک خاص
    public function show(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        return Inertia::render('TaskDetail', [
            'task' => $task,
        ]);
    }


    // ایجاد یک تسک جدید (برای پروژه یا شخصی)
    public function store(StoreTaskRequest $request)
    {
        $projectId = $request->filled('project_id')
        ? (int) $request->input('project_id')
        : null;
        $this->authorize('create', [Task::class, $projectId]);
        $user = $request->user();

        $data = $request->validated();

        Task::create([
            'project_id'     => $projectId,  // تسک پروژه‌ای
            'completed_by_id'=> null,          // تا انجام نشده، به فرد خاصی نسبت داده نمی‌شود
            'created_by_id'  => $user->id,     // هر کسی ساخته را نگه می‌داریم
            'title'          => $data['title'],
            'description'    => $data['description'] ?? null,
            'estimation'     => $data['estimation'],
            'status'         => $data['status'] ?? 'todo',
        ]);

        return redirect()->back();
    }

    // ۳) انجام دادن تسک (تغییر وضعیت به done)
    public function markAsCompleted(Request $request, Task $task)
    {
        $user = $request->user();
        $this->authorize('markAsCompleted', $task);
        $task->update([
            'status' => 'done',
            'completed_by_id' => $user->id,
        ]);
        return redirect()->back();
    }
    // به روزرسانی تسک (عنوان، توضیحات، برآورد)
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validated();
        $task->update($data);
        return redirect()->back();
    }
    
}
