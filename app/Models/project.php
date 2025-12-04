<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class project extends Model
{
    protected $fillable = ['owner_id', 'name', 'description', 'deadline', 'status'];

    // سازنده پروژه
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // اعضای پروژه
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    // تسک‌های مربوط به این پروژه
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
