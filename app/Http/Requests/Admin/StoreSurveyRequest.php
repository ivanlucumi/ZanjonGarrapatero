<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
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
            'description' => ['nullable', 'string', 'max:2000'],
            'target_vereda' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'string', 'in:active,draft,closed'],
            'questions' => ['required', 'array', 'min:1'],
            'questions.*.id' => ['required', 'string', 'max:50'],
            'questions.*.label' => ['required', 'string', 'max:255'],
            'questions.*.type' => ['required', 'string', 'in:text,textarea,number,select,radio'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.required' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'título de la encuesta',
            'category' => 'categoría',
            'questions' => 'preguntas',
        ];
    }
}
