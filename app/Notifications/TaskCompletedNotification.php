<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(public Task $task)
    {
    }

    public function via($notifiable): array
    {
        return ['database']; // بعداً می‌تونی 'mail' هم اضافه کنی
    }

    public function toArray($notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'message' => "تسک «{$this->task->title}» انجام شد.",
        ];
    }
}
