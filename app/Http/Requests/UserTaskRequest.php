<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserTaskRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'title' => 'required|string|max:255', 
            'description' => 'nullable|string|max:1000', 
            'status' => 'required|in:pending,completed',
            'due_date' => 'required|date|after_or_equal:today', 
        ];
    }
}
