<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole(UserRole::SuperAdmin, UserRole::Admin) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:150'],
            'event_date' => ['nullable', 'date'],
            'status' => ['required', 'string', 'max:40'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
