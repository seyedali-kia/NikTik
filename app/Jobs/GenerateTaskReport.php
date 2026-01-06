<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateTaskReport
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function handle(): array
    {
        $counts = $this->user->personalTasks()
            ->selectRaw("status, COUNT(*) as total")
            ->groupBy("status")
            ->pluck("total", "status")
            ->toArray();

        return $counts; // اگر خواستی از controller هم استفاده کنی
    }
}
