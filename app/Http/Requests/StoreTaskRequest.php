<?php

namespace App\Http\Requests;

use App\Enums\Task\Priority;
use App\Enums\Task\Status;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

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
            'title' => ['required', 'max:200'],
            'description' => ['required', 'max:500'],
            'due_date' => ['required', 'date', 'date_format:Y-m-d'],
            'priority' => ['required', new Enum(Priority::class)],
            'assigned_to' => ['required', 'exists:users,id'],
        ];
    }
}
