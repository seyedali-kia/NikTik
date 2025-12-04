<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'user_id',
        'created_by_id',
        'title',
        'description',
        'estimation',
        'status',
        'done_at',
    ];

    // اگر تسک پروژه‌ای باشد به این پروژه تعلق دارد
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // یوزری که "مالک / انجام‌دهنده" تسک است (یا شخصی یا کسی که انجام داده)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // یوزری که تسک رو ساخته
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    // تشخیص تسک شخصی
    public function isPersonal(): bool
    {
        return is_null($this->project_id);
    }

    // تشخیص تسک پروژه‌ای
    public function isProjectTask(): bool
    {
        return ! is_null($this->project_id);
    }
}
