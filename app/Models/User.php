<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_path',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    // 🔹 پروژه‌هایی که مالکشون این یوزره (projects.owner_id = users.id)
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    // 🔹 پروژه‌هایی که این کاربر توشون عضو هست (pivot table: project_user)
    public function involvedProjects()
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    // 🔹 تسک‌هایی که این یوزر ساخته (tasks.created_by_id)
    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    
    // تسک‌هایی که این کاربر انجام داده
    public function completedTasks()
    {
        return $this->hasMany(Task::class, 'completed_by_id');
    }

    // تسک‌های شخصی (طبق قرارداد شما: project_id = null و ساخته‌ی خودش)
    public function personalTasks()
    {
        return $this->createdTasks()->whereNull('project_id');
    }
}
