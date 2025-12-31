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
}
