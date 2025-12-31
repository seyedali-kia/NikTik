<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'created_by_id',
        'completed_by_id',
        'title',
        'description',
        'estimation',
        'status',
        'done_at',
    ];

    protected $casts = [
        'done_at' => 'datetime',
    ];

    // اگر تسک پروژه‌ای باشد به این پروژه تعلق دارد
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // یوزری که تسک رو ساخته
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id')->withTrashed();
    }

    // یوزری که تسک را انجام داده است
    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by_id')->withTrashed();
    }

    // تشخیص تسک شخصی
    public function isPersonal(): bool
    {
        return is_null($this->project_id);
    }

    public function isProjectTask(): bool
    {
        return ! is_null($this->project_id);
    }

    // Scope ها (اختیاری ولی خیلی کاربردی)
    public function scopePersonal($query)
    {
        return $query->whereNull('project_id');
    }

    public function scopeProjectTasks($query)
    {
        return $query->whereNotNull('project_id');
    }

    public function scopeDone($query)
    {
        return $query->where('status', 'done');
    }
}
