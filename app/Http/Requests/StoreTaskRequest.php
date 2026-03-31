<?php

namespace App\Http\Requests;

use App\Enums\Priority;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks')->where(function ($query) {
                    return $query->where('due_date', $this->due_date);
                }),
            ],
            'due_date' => [
                'required',
                'date',
                'date_format:Y-m-d',
                'after_or_equal:today'
            ],
            'priority' => [
                'required',
                Rule::in(array_column(Priority::cases(), 'value')),
            ]
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'A task with this title already exists for this due date.',
            'due_date.after_or_equal' => 'Due date must be today or in the future.',
            'priority.in' => 'Priority must be low, medium or high.'
        ];
    }
}
