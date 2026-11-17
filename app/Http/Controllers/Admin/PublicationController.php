<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePublicationRequest;
use App\Http\Requests\Admin\UpdatePublicationRequest;
use App\Models\Publication;
use App\Models\User;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicationController extends Controller
{
    /**
     * Display a listing of publications (CMS).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $category = $request->query('category');

        $query = Publication::query()->with('author');

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status === 'inactive') {
            $query->where('is_visible', false);
        } elseif ($status === 'active') {
            $query->where('is_visible', true);
        } else {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $publications = $query->latest('id')->paginate(12)->withQueryString();

        return view('admin.publications.index', [
            'publications' => $publications,
            'search' => $search,
            'status' => $status,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new publication.
     */
    public function create(): View
    {
        return view('admin.publications.create');
    }

    /**
     * Store a newly created publication.
     */
    public function store(StorePublicationRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $publication = new Publication;
        $publication->title = $validated['title'];
        $publication->slug = Str::slug($validated['title']).'-'.Str::random(6);
        $publication->category = $validated['category'];
        $publication->summary = $validated['summary'] ?? null;
        $publication->content = $validated['content'];
        $publication->image_url = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $publication->image_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'news', 1600, 1000);
        }

        if ($request->hasFile('attachment_file')) {
            $attachment = FileUploadService::storeDocument($request->file('attachment_file'), 'news-attachments');
            $publication->attachment_url = $attachment['url'];
            $publication->attachment_name = $attachment['name'];
            $publication->attachment_size = $attachment['size'];
        }

        $publication->is_visible = $request->boolean('is_visible', true);
        $publication->published_at = now();
        $publication->author_id = $user->id;
        $publication->save();

        AuditService::log(
            action: 'crear_publicacion',
            entity: $publication,
            description: "Publicación '{$publication->title}' creada y registrada en plataforma.",
            properties: ['category' => $publication->category, 'is_visible' => $publication->is_visible]
        );

        return redirect()->route('admin.publications.index')
            ->with('success', "La publicación '{$publication->title}' ha sido creada exitosamente.");
    }

    /**
     * Show the form for editing the specified publication.
     */
    public function edit(int $id): View
    {
        $publication = Publication::withTrashed()->findOrFail($id);

        return view('admin.publications.edit', [
            'publication' => $publication,
        ]);
    }

    /**
     * Update the specified publication.
     */
    public function update(UpdatePublicationRequest $request, int $id): RedirectResponse
    {
        $publication = Publication::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $publication->title = $validated['title'];
        $publication->category = $validated['category'];
        $publication->summary = $validated['summary'] ?? null;
        $publication->content = $validated['content'];
        $publication->image_url = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $publication->image_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'news', 1600, 1000);
        }

        if ($request->hasFile('attachment_file')) {
            $attachment = FileUploadService::storeDocument($request->file('attachment_file'), 'news-attachments');
            $publication->attachment_url = $attachment['url'];
            $publication->attachment_name = $attachment['name'];
            $publication->attachment_size = $attachment['size'];
        }

        $publication->is_visible = $request->boolean('is_visible', true);
        $publication->save();

        AuditService::log(
            action: 'actualizar_publicacion',
            entity: $publication,
            description: "Publicación '{$publication->title}' actualizada.",
            properties: ['category' => $publication->category, 'is_visible' => $publication->is_visible]
        );

        return redirect()->route('admin.publications.index')
            ->with('success', "Publicación '{$publication->title}' actualizada correctamente.");
    }

    /**
     * Toggle visibility (is_visible) of the publication.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $publication = Publication::withTrashed()->findOrFail($id);
        $publication->is_visible = ! $publication->is_visible;
        $publication->save();

        $stateText = $publication->is_visible ? 'visible en el portal público' : 'oculta del portal público';

        AuditService::log(
            action: 'cambio_visibilidad_publicacion',
            entity: $publication,
            description: "Estado de publicación '{$publication->title}' cambiado a {$stateText}.",
            properties: ['is_visible' => $publication->is_visible]
        );

        return back()->with('success', "La publicación ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the publication without destroying history.
     */
    public function destroy(Publication $publication): RedirectResponse
    {
        $title = $publication->title;
        $publication->delete(); // Soft delete sets deleted_at

        AuditService::log(
            action: 'inactivar_publicacion',
            entity: $publication,
            description: "Publicación '{$title}' inactivada mediante SoftDelete (conservada en histórico).",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Publicación '{$title}' inactivada (resguardada en histórico con SoftDelete).");
    }

    /**
     * Restore a soft-deleted publication.
     */
    public function restore(int $id): RedirectResponse
    {
        $publication = Publication::onlyTrashed()->findOrFail($id);
        $publication->restore();

        AuditService::log(
            action: 'restaurar_publicacion',
            entity: $publication,
            description: "Publicación '{$publication->title}' restaurada desde la papelera de histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Publicación '{$publication->title}' restaurada y activa nuevamente.");
    }
}
