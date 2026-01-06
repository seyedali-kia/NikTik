<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterTaskRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [ 
            'status' => ['nullable', 'in:todo,doing,done'],
            'search' => ['nullable', 'string', 'max:100'],
            'sort'   => ['nullable', 'in:newest,oldest']
        ];
    }
}
