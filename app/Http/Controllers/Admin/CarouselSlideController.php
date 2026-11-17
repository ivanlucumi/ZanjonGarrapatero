<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCarouselSlideRequest;
use App\Http\Requests\Admin\UpdateCarouselSlideRequest;
use App\Models\CarouselSlide;
use App\Models\User;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CarouselSlideController extends Controller
{
    /**
     * Display a listing of carousel slides.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $type = $request->query('type', 'all');

        $query = CarouselSlide::query()->with('creator');

        if ($type !== 'all' && in_array($type, ['image', 'video', 'none'], true)) {
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
                    ->orWhere('subtitle', 'like', "%{$search}%")
                    ->orWhere('badge_text', 'like', "%{$search}%");
            });
        }

        $slides = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.carousel.index', [
            'slides' => $slides,
            'search' => $search,
            'status' => $status,
            'type' => $type,
        ]);
    }

    /**
     * Show the form for creating a new carousel slide.
     */
    public function create(): View
    {
        return view('admin.carousel.create');
    }

    /**
     * Store a newly created carousel slide.
     */
    public function store(StoreCarouselSlideRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $slide = new CarouselSlide;
        $slide->title = $validated['title'];
        $slide->subtitle = $validated['subtitle'] ?? null;
        $slide->badge_text = $validated['badge_text'] ?? null;
        $slide->type = $validated['type'];
        $slide->media_url = $validated['media_url'] ?? null;
        $slide->poster_url = $validated['poster_url'] ?? null;

        // Process direct media file upload with optimization or weight limits
        if ($request->hasFile('media_file')) {
            if ($slide->type === 'image') {
                $slide->media_url = FileUploadService::optimizeAndStoreImage($request->file('media_file'), 'carousel', 1920, 1080);
            } elseif ($slide->type === 'video') {
                $slide->media_url = FileUploadService::storeVideo($request->file('media_file'), 'carousel');
            }
        }

        if ($request->hasFile('poster_file')) {
            $slide->poster_url = FileUploadService::optimizeAndStoreImage($request->file('poster_file'), 'carousel', 1280, 720);
        }

        $slide->projection_title = $validated['projection_title'] ?? null;
        $slide->primary_button_text = $validated['primary_button_text'] ?? null;
        $slide->primary_button_url = $validated['primary_button_url'] ?? null;
        $slide->secondary_button_text = $validated['secondary_button_text'] ?? null;
        $slide->secondary_button_url = $validated['secondary_button_url'] ?? null;
        $slide->sort_order = $validated['sort_order'] ?? 0;
        $slide->is_visible = $request->boolean('is_visible', true);
        $slide->created_by = $user->id;
        $slide->save();

        AuditService::log(
            action: 'crear_slide_carrusel',
            entity: $slide,
            description: "Diapositiva '{$slide->title}' creada para el carrusel principal.",
            properties: ['type' => $slide->type, 'is_visible' => $slide->is_visible]
        );

        return redirect()->route('admin.carousel.index')
            ->with('success', "Diapositiva '{$slide->title}' registrada exitosamente en el carrusel.");
    }

    /**
     * Show the form for editing the carousel slide.
     */
    public function edit(int $id): View
    {
        $slide = CarouselSlide::withTrashed()->findOrFail($id);

        return view('admin.carousel.edit', [
            'slide' => $slide,
        ]);
    }

    /**
     * Update the specified carousel slide.
     */
    public function update(UpdateCarouselSlideRequest $request, int $id): RedirectResponse
    {
        $slide = CarouselSlide::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $slide->title = $validated['title'];
        $slide->subtitle = $validated['subtitle'] ?? null;
        $slide->badge_text = $validated['badge_text'] ?? null;
        $slide->type = $validated['type'];
        $slide->media_url = $validated['media_url'] ?? null;
        $slide->poster_url = $validated['poster_url'] ?? null;

        // Process direct media file upload with optimization or weight limits
        if ($request->hasFile('media_file')) {
            if ($slide->type === 'image') {
                $slide->media_url = FileUploadService::optimizeAndStoreImage($request->file('media_file'), 'carousel', 1920, 1080);
            } elseif ($slide->type === 'video') {
                $slide->media_url = FileUploadService::storeVideo($request->file('media_file'), 'carousel');
            }
        }

        if ($request->hasFile('poster_file')) {
            $slide->poster_url = FileUploadService::optimizeAndStoreImage($request->file('poster_file'), 'carousel', 1280, 720);
        }

        $slide->projection_title = $validated['projection_title'] ?? null;
        $slide->primary_button_text = $validated['primary_button_text'] ?? null;
        $slide->primary_button_url = $validated['primary_button_url'] ?? null;
        $slide->secondary_button_text = $validated['secondary_button_text'] ?? null;
        $slide->secondary_button_url = $validated['secondary_button_url'] ?? null;
        $slide->sort_order = $validated['sort_order'] ?? 0;
        $slide->is_visible = $request->boolean('is_visible', true);
        $slide->save();

        AuditService::log(
            action: 'actualizar_slide_carrusel',
            entity: $slide,
            description: "Diapositiva '{$slide->title}' del carrusel actualizada.",
            properties: ['type' => $slide->type, 'is_visible' => $slide->is_visible]
        );

        return redirect()->route('admin.carousel.index')
            ->with('success', "Diapositiva '{$slide->title}' actualizada correctamente.");
    }

    /**
     * Toggle visibility of the carousel slide.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $slide = CarouselSlide::withTrashed()->findOrFail($id);
        $slide->is_visible = ! $slide->is_visible;
        $slide->save();

        $stateText = $slide->is_visible ? 'visible en el carrusel' : 'oculta del carrusel';

        AuditService::log(
            action: 'cambio_visibilidad_slide',
            entity: $slide,
            description: "Visibilidad de diapositiva '{$slide->title}' cambiada a {$stateText}.",
            properties: ['is_visible' => $slide->is_visible]
        );

        return back()->with('success', "La diapositiva ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the carousel slide.
     */
    public function destroy(CarouselSlide $slide): RedirectResponse
    {
        $title = $slide->title;
        $slide->delete();

        AuditService::log(
            action: 'inactivar_slide_carrusel',
            entity: $slide,
            description: "Diapositiva '{$title}' inactivada mediante SoftDelete.",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Diapositiva '{$title}' inactivada (resguardada con SoftDelete).");
    }

    /**
     * Restore a soft-deleted carousel slide.
     */
    public function restore(int $id): RedirectResponse
    {
        $slide = CarouselSlide::onlyTrashed()->findOrFail($id);
        $slide->restore();

        AuditService::log(
            action: 'restaurar_slide_carrusel',
            entity: $slide,
            description: "Diapositiva '{$slide->title}' restaurada desde histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Diapositiva '{$slide->title}' restaurada y activa nuevamente.");
    }
}
