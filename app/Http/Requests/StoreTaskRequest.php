<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
            'project_id' => 'nullable|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'estimation' => 'nullable|integer|min:1',
            'status' => 'required|in:todo,doing,done',
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
            'title.required' => 'عنوان تسک الزامی است.',
            'title.max' => 'عنوان تسک نباید بیشتر از 255 کاراکتر باشد.',
            'estimation.integer' => 'برآورد سختی باید عدد صحیح باشد.',
            'estimation.min' => 'برآورد سختی باید حداقل 1 باشد.',
            'status.required' => 'وضعیت تسک الزامی است.',
            'status.in' => 'وضعیت انتخاب شده معتبر نیست.',
            'project_id.exists' => 'پروژه انتخاب شده وجود ندارد.',
        ];
    }
}
