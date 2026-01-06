<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(private TaskService $taskService)
    {}



    // نمایش تسک‌های شخصی کاربر
    public function index(FilterTaskRequest $request)
    {
        $user = $request->user();

        $filters = $request->validated();
        $tasksQuery = $user->personalTasks();

        if (!empty($filters['status'])) {
            $tasksQuery->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $tasksQuery->where('title', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'newest') {
                $tasksQuery->orderBy('created_at', 'desc');
            } elseif ($filters['sort'] === 'oldest') {
                $tasksQuery->orderBy('created_at', 'asc');
            }
        }else {
            $tasksQuery->orderbyRaw('FIELD(status, "doing", "todo", "done")')
            ->orderBy('created_at', 'desc');
        }
        $tasks = $tasksQuery->get();
        return Inertia::render('Tasks', [
            'tasks' => $tasks,
            'filters' => [
                'status' => $filters['status'] ?? null,
                'search' => $filters['search'] ?? null,
                'sort'   => $filters['sort'] ?? null,
        ],
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
        try {
            $projectId = $request->filled('project_id')
            ? (int) $request->input('project_id')
            : null;
            $this->authorize('create', [Task::class, $projectId]);
            $user = $request->user();

            $data = $request->validated();

            $this->taskService->createTask($user, $data, $projectId);

            return redirect()->back()->with('flash', [
                'type' => 'success',
                'message' => 'تسک با موفقیت ایجاد شد.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Task creation failed', [
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
         ]);
            return redirect()->back()->with('flash', [
                'type' => 'error',
                'message' => 'خطا در ایجاد تسک' 
            ]);
        }
    }

    // ۳) انجام دادن تسک (تغییر وضعیت به done)
    public function markAsCompleted(Request $request, Task $task)
    {
        try {
            $this->authorize('markAsCompleted', $task);
            $this->taskService->completeTask($request->user(), $task);
            return redirect()->back()->with('flash', [
                'type' => 'success',
                'message' => 'تسک با موفقیت انجام شد.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Task completion failed', [
                'task_id' => $task->id,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
         ]);
            return redirect()->back()->with('flash', [
                'type' => 'error',
                'message' => 'خطا در انجام تسک' 
            ]);
        }
    }

    public function markAsStarted(Request $request, Task $task)
    {
        try {
            $this->authorize('markAsCompleted', $task);
            $this->taskService->startTask($request->user(), $task);
            return redirect()->back()->with('flash', [
                'type' => 'success',
                'message' => 'تسک با موفقیت شروع شد.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Task start failed', [
                'task_id' => $task->id,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
         ]);
            return redirect()->back()->with('flash', [
                'type' => 'error',
                'message' => 'خطا در شروع تسک' 
            ]);
        }
    }

    // به روزرسانی تسک (عنوان، توضیحات، برآورد)
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
            $this->taskService->updateTask($task, $request->validated());
            return redirect()->back()->with('flash', [
                'type' => 'success',
                'message' => 'تسک با موفقیت به‌روزرسانی شد.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Task update failed', [
                'task_id' => $task->id,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
         ]);
            return redirect()->back()->with('flash', [
                'type' => 'error',
                'message' => 'خطا در به‌روزرسانی تسک' 
            ]);
        }
    }


    public function destroy(Request $request, Task $task)
    {
        try {
            $this->authorize('update', $task);
            $this->taskService->deleteTask($task);
            return redirect()->back()->with('flash', [
                'type' => 'success',
                'message' => 'تسک با موفقیت حذف شد.',
            ]);
        } catch (\Throwable $e) {
            Log::error('Task delete failed', [
                'task_id' => $task->id,
                'user_id' => $request->user()?->id,
                'error' => $e->getMessage(),
         ]);
            return redirect()->back()->with('flash', [
                'type' => 'error',
                'message' => 'خطا در حذف تسک' 
            ]);
        }
    }
    
}
