<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    // فرم ساخت پروژه (اگر از Inertia استفاده می‌کنی، می‌تونی از این هم استفاده کنی)
    public function create()
    {
        return inertia('Projects/Create');
    }

    // ذخیره پروژه جدید
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = $request->user();
        // ۱) ساخت پروژه
        $project = Project::create([
            'owner_id'    => $user->id,
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        // ۲) افزودن owner به اعضای پروژه
        $project->users()->attach($user->id);

        return redirect()->route('dashboard');
    }
    public function addMember(Request $request, Project $project)
    {
        $currentUser = $request->user();

        // فقط صاحب پروژه اجازه مدیریت اعضا را دارد
        abort_unless($currentUser->id === $project->owner_id, 403);

        $data = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                // نذاریم خودش رو دوباره اضافه کنه (ضروری نیست ولی قشنگه)
                Rule::notIn([$project->owner_id]),
            ],
        ]);

        // syncWithoutDetaching یعنی اگر قبلاً عضو بوده، دوباره اضافه‌اش نکن
        $project->users()->syncWithoutDetaching($data['user_id']);

        return back();
    }
}
