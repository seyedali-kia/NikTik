<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
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
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],

            'status' => ['sometimes', 'required', 'in:planned,in_progress,completed,cancelled'],
            'deadline' => ['sometimes', 'nullable', 'date'],

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
            'members.array' => 'اعضا باید به صورت لیست باشند.',
            'members.*.integer' => 'شناسه عضو باید عدد صحیح باشد.',
            'members.*.exists' => 'کاربر انتخاب شده وجود ندارد.',
        ];
    }
}
