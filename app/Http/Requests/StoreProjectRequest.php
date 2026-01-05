<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'deadline' => 'nullable|date|after_or_equal:today',
            
            'members' => ['sometimes', 'array'],
            'members.*' => ['sometimes', 'integer', 'exists:users,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'نام پروژه الزامی است.',
            'name.max' => 'نام پروژه نباید بیشتر از 255 کاراکتر باشد.',
            'status.required' => 'وضعیت پروژه الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',
            'deadline.date' => 'تاریخ ددلاین معتبر نیست.',
            'deadline.after_or_equal' => 'تاریخ ددلاین نمی‌تواند قبل از امروز باشد.',
            'members.array' => 'اعضا باید به صورت لیست باشند.',
            'members.*.integer' => 'شناسه عضو باید عدد صحیح باشد.',
            'members.*.exists' => 'کاربر انتخاب شده وجود ندارد.',
        ];
    }
}
