<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDocumentRequest;
use App\Http\Requests\Admin\UpdateDocumentRequest;
use App\Models\Document;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of institutional documents.
     */
    public function index(Request $request): View
    {
        $query = Document::query()->withTrashed();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->string('category')->toString());
        }

        $status = $request->string('status', 'active')->toString();
        if ($status === 'active') {
            $query->whereNull('deleted_at');
        } elseif ($status === 'inactive') {
            $query->onlyTrashed();
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        $categories = [
            'Normatividad y Ley 70',
            'Actas de Asamblea',
            'Planes de Manejo Ambiental',
            'Formatos y Trámites',
            'Gacetas y Resoluciones',
            'Censo y Cartografía',
        ];

        return view('admin.documents.index', [
            'documents' => $documents,
            'categories' => $categories,
            'currentSearch' => $request->string('search')->toString(),
            'currentCategory' => $request->string('category')->toString(),
            'currentStatus' => $status,
        ]);
    }

    /**
     * Show the form for creating a new document.
     */
    public function create(): View
    {
        $categories = [
            'Normatividad y Ley 70',
            'Actas de Asamblea',
            'Planes de Manejo Ambiental',
            'Formatos y Trámites',
            'Gacetas y Resoluciones',
            'Censo y Cartografía',
        ];

        return view('admin.documents.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created document in storage.
     */
    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $fileInfo = FileUploadService::storeDocument($request->file('document_file'), 'documents');

        $document = new Document;
        $document->title = $validated['title'];
        $document->category = $validated['category'];
        $document->description = $validated['description'] ?? null;
        $document->file_path = $fileInfo['path'];
        $document->file_name = $fileInfo['name'];
        $document->file_size = $fileInfo['size'];
        $document->mime_type = $fileInfo['mime'];
        $document->is_visible = $request->boolean('is_visible', true);
        $document->created_by = $request->user()?->id;
        $document->save();

        AuditService::log(
            action: 'crear_documento',
            entity: $document,
            description: "Documento institucional '{$document->title}' cargado al repositorio ({$document->formattedSize()}).",
            properties: [
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
                'category' => $document->category,
            ]
        );

        return redirect()->route('admin.documents.index')
            ->with('success', "Documento '{$document->title}' cargado exitosamente.");
    }

    /**
     * Show the form for editing the document.
     */
    public function edit(int $id): View
    {
        $document = Document::withTrashed()->findOrFail($id);

        $categories = [
            'Normatividad y Ley 70',
            'Actas de Asamblea',
            'Planes de Manejo Ambiental',
            'Formatos y Trámites',
            'Gacetas y Resoluciones',
            'Censo y Cartografía',
        ];

        return view('admin.documents.edit', [
            'document' => $document,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified document in storage.
     */
    public function update(UpdateDocumentRequest $request, int $id): RedirectResponse
    {
        $document = Document::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $document->title = $validated['title'];
        $document->category = $validated['category'];
        $document->description = $validated['description'] ?? null;
        $document->is_visible = $request->boolean('is_visible', true);

        if ($request->hasFile('document_file')) {
            // Delete previous file safely
            FileUploadService::deleteStoredFile($document->file_path);

            $fileInfo = FileUploadService::storeDocument($request->file('document_file'), 'documents');
            $document->file_path = $fileInfo['path'];
            $document->file_name = $fileInfo['name'];
            $document->file_size = $fileInfo['size'];
            $document->mime_type = $fileInfo['mime'];
        }

        $document->save();

        AuditService::log(
            action: 'actualizar_documento',
            entity: $document,
            description: "Documento institucional '{$document->title}' actualizado.",
            properties: [
                'file_name' => $document->file_name,
                'file_size' => $document->file_size,
                'category' => $document->category,
                'is_visible' => $document->is_visible,
            ]
        );

        return redirect()->route('admin.documents.index')
            ->with('success', "Documento '{$document->title}' actualizado correctamente.");
    }

    /**
     * Toggle visibility of the document.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $document = Document::withTrashed()->findOrFail($id);
        $document->is_visible = ! $document->is_visible;
        $document->save();

        $statusText = $document->is_visible ? 'visible para la comunidad' : 'oculto al público';

        AuditService::log(
            action: 'cambio_visibilidad_documento',
            entity: $document,
            description: "Visibilidad de '{$document->title}' cambiada a {$statusText}.",
            properties: ['is_visible' => $document->is_visible]
        );

        return back()->with('success', "El documento ahora está {$statusText}.");
    }

    /**
     * Soft delete (inactivate) the document.
     */
    public function destroy(Document $document): RedirectResponse
    {
        $title = $document->title;
        $document->delete();

        AuditService::log(
            action: 'inactivar_documento',
            entity: $document,
            description: "Documento '{$title}' inactivado mediante SoftDelete.",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Documento '{$title}' inactivado (resguardado con SoftDelete).");
    }

    /**
     * Restore a soft-deleted document.
     */
    public function restore(int $id): RedirectResponse
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();

        AuditService::log(
            action: 'restaurar_documento',
            entity: $document,
            description: "Documento '{$document->title}' restaurado desde el archivo inactivo.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Documento '{$document->title}' restaurado y activo nuevamente.");
    }

    /**
     * Download the document file securely and track download count.
     */
    public function download(int $id): StreamedResponse|RedirectResponse
    {
        $document = Document::withTrashed()->findOrFail($id);

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'El archivo no fue encontrado en el almacenamiento.');
        }

        $document->increment('download_count');

        AuditService::log(
            action: 'descarga_documento_admin',
            entity: $document,
            description: "Descarga administrativa del documento '{$document->title}'.",
            properties: ['download_count' => $document->download_count]
        );

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
