<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMediaItemRequest extends FormRequest
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
            'type' => ['required', 'string', 'in:image,video'],
            'category' => ['required', 'string', 'max:80'],
            'caption' => ['nullable', 'string', 'max:255'],
            'file_url' => ['nullable', 'string', 'max:500'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,ogg,mov', 'max:51200'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'thumbnail_url' => ['nullable', 'string', 'max:500'],
            'duration' => ['nullable', 'string', 'max:30'],
            'is_featured' => ['nullable', 'boolean'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
