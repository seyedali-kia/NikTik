<?php

namespace App\Listeners;

use App\Events\TaskCompleted;
use App\Notifications\TaskCompletedNotification;

class SendTaskCompletedNotification
{
    public function handle(TaskCompleted $event): void
    {
        $task = $event->task;

        // مثال: به سازنده‌ی تسک اطلاع بده
        $creator = $task->creator; // باید relationship داشته باشی (پایین توضیح میدم)

        if ($creator) {
            $creator->notify(new TaskCompletedNotification($task));
        }
    }
}
