<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMediaItemRequest;
use App\Http\Requests\Admin\UpdateMediaItemRequest;
use App\Models\MediaItem;
use App\Models\User;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MediaItemController extends Controller
{
    /**
     * Display a listing of media items (gallery and videos).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $type = $request->query('type', 'all');
        $status = $request->query('status', 'all');

        $query = MediaItem::query()->with('creator');

        if ($type === 'image' || $type === 'video') {
            $query->where('type', $type);
        }

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
                    ->orWhere('caption', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $mediaItems = $query->latest('id')->paginate(12)->withQueryString();

        return view('admin.media.index', [
            'mediaItems' => $mediaItems,
            'search' => $search,
            'type' => $type,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new media item.
     */
    public function create(): View
    {
        return view('admin.media.create');
    }

    /**
     * Store a newly created media item.
     */
    public function store(StoreMediaItemRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $item = new MediaItem;
        $item->title = $validated['title'];
        $item->slug = Str::slug($validated['title']).'-'.Str::random(6);
        $item->type = $validated['type'];
        $item->category = $validated['category'];
        $item->caption = $validated['caption'] ?? null;

        if ($request->hasFile('image_file')) {
            $item->file_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'gallery', 1600, 1060);
        } elseif ($request->hasFile('video_file')) {
            $item->file_url = FileUploadService::storeVideo($request->file('video_file'), 'videos');
        } else {
            $item->file_url = $validated['file_url'] ?? '';
        }

        if ($request->hasFile('thumbnail_file')) {
            $item->thumbnail_url = FileUploadService::optimizeAndStoreImage($request->file('thumbnail_file'), 'videos', 1280, 720);
        } else {
            $item->thumbnail_url = $validated['thumbnail_url'] ?? null;
        }

        $item->duration = $validated['duration'] ?? null;
        $item->is_featured = $request->boolean('is_featured', false);
        $item->is_visible = $request->boolean('is_visible', true);
        $item->created_by = $user->id;
        $item->save();

        AuditService::log(
            action: 'crear_multimedia',
            entity: $item,
            description: "Elemento multimedia ({$item->type}) '{$item->title}' registrado.",
            properties: ['type' => $item->type, 'category' => $item->category]
        );

        return redirect()->route('admin.media.index')
            ->with('success', "El contenido multimedia '{$item->title}' ha sido registrado exitosamente.");
    }

    /**
     * Show the form for editing the media item.
     */
    public function edit(int $id): View
    {
        $item = MediaItem::withTrashed()->findOrFail($id);

        return view('admin.media.edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified media item.
     */
    public function update(UpdateMediaItemRequest $request, int $id): RedirectResponse
    {
        $item = MediaItem::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $item->title = $validated['title'];
        $item->type = $validated['type'];
        $item->category = $validated['category'];
        $item->caption = $validated['caption'] ?? null;

        if ($request->hasFile('image_file')) {
            $item->file_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'gallery', 1600, 1060);
        } elseif ($request->hasFile('video_file')) {
            $item->file_url = FileUploadService::storeVideo($request->file('video_file'), 'videos');
        } else {
            $item->file_url = $validated['file_url'] ?? '';
        }

        if ($request->hasFile('thumbnail_file')) {
            $item->thumbnail_url = FileUploadService::optimizeAndStoreImage($request->file('thumbnail_file'), 'videos', 1280, 720);
        } else {
            $item->thumbnail_url = $validated['thumbnail_url'] ?? null;
        }

        $item->duration = $validated['duration'] ?? null;
        $item->is_featured = $request->boolean('is_featured', false);
        $item->is_visible = $request->boolean('is_visible', true);
        $item->save();

        AuditService::log(
            action: 'actualizar_multimedia',
            entity: $item,
            description: "Elemento multimedia '{$item->title}' actualizado.",
            properties: ['type' => $item->type, 'is_visible' => $item->is_visible]
        );

        return redirect()->route('admin.media.index')
            ->with('success', "Contenido multimedia '{$item->title}' actualizado correctamente.");
    }

    /**
     * Toggle visibility (is_visible) of the media item.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $item = MediaItem::withTrashed()->findOrFail($id);
        $item->is_visible = ! $item->is_visible;
        $item->save();

        $stateText = $item->is_visible ? 'visible en la galería/videos' : 'oculto del portal público';

        AuditService::log(
            action: 'cambio_visibilidad_multimedia',
            entity: $item,
            description: "Visibilidad de '{$item->title}' cambiada a {$stateText}.",
            properties: ['is_visible' => $item->is_visible]
        );

        return back()->with('success', "El elemento ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the media item.
     */
    public function destroy(MediaItem $mediaItem): RedirectResponse
    {
        $title = $mediaItem->title;
        $mediaItem->delete();

        AuditService::log(
            action: 'inactivar_multimedia',
            entity: $mediaItem,
            description: "Elemento multimedia '{$title}' inactivado mediante SoftDelete (en histórico).",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Elemento '{$title}' inactivado (conservado con SoftDelete).");
    }

    /**
     * Restore a soft-deleted media item.
     */
    public function restore(int $id): RedirectResponse
    {
        $item = MediaItem::onlyTrashed()->findOrFail($id);
        $item->restore();

        AuditService::log(
            action: 'restaurar_multimedia',
            entity: $item,
            description: "Elemento multimedia '{$item->title}' restaurado desde histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Elemento '{$item->title}' restaurado exitosamente.");
    }
}
