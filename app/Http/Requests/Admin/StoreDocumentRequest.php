<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
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
            'category' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'document_file' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt', 'max:20480'],
            'is_visible' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'El título del documento institucional es obligatorio.',
            'category.required' => 'Selecciona o escribe una categoría para el documento.',
            'document_file.required' => 'Debes adjuntar el archivo del documento institucional.',
            'document_file.file' => 'El elemento cargado debe ser un archivo válido.',
            'document_file.mimes' => 'Formatos permitidos: PDF, Word (doc, docx), Excel (xls, xlsx), PowerPoint (ppt, pptx), ZIP, RAR o TXT.',
            'document_file.max' => 'El documento no puede superar los 20 MB de peso para garantizar descargas veloces.',
        ];
    }
}
