<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTerritoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role->canManageContent();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'sector_badge' => ['nullable', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:2000'],
            'estimated_families' => ['nullable', 'integer', 'min:0'],
            'watersheds' => ['nullable', 'string', 'max:255'],
            'main_activities' => ['nullable', 'string', 'max:255'],
            'image_url' => ['nullable', 'string', 'max:1000'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
