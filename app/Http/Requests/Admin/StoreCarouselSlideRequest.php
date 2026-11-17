<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCarouselSlideRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:1000'],
            'badge_text' => ['nullable', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:image,video,none'],
            'media_url' => ['nullable', 'string', 'max:1000'],
            'media_file' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,mp4,webm,ogg,mov', 'max:51200'],
            'poster_url' => ['nullable', 'string', 'max:1000'],
            'poster_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'projection_title' => ['nullable', 'string', 'max:255'],
            'primary_button_text' => ['nullable', 'string', 'max:100'],
            'primary_button_url' => ['nullable', 'string', 'max:255'],
            'secondary_button_text' => ['nullable', 'string', 'max:100'],
            'secondary_button_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }
}
