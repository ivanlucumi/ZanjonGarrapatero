<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityRequest;
use App\Http\Requests\Admin\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    /**
     * Display a listing of community activities.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $category = $request->query('category');

        $query = Activity::query()->with('creator');

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
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        $activities = $query->latest('id')->paginate(12)->withQueryString();

        return view('admin.activities.index', [
            'activities' => $activities,
            'search' => $search,
            'status' => $status,
            'category' => $category,
        ]);
    }

    /**
     * Show the form for creating a new activity.
     */
    public function create(): View
    {
        return view('admin.activities.create');
    }

    /**
     * Store a newly created activity.
     */
    public function store(StoreActivityRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $activity = new Activity;
        $activity->title = $validated['title'];
        $activity->slug = Str::slug($validated['title']).'-'.Str::random(6);
        $activity->category = $validated['category'];
        $activity->description = $validated['description'];
        $activity->location = $validated['location'] ?? null;
        $activity->event_date = $validated['event_date'] ?? null;
        $activity->status = $validated['status'];
        $activity->is_visible = $request->boolean('is_visible', true);
        $activity->created_by = $user->id;
        $activity->save();

        AuditService::log(
            action: 'crear_actividad',
            entity: $activity,
            description: "Actividad comunitaria '{$activity->title}' programada y registrada.",
            properties: ['category' => $activity->category, 'event_date' => $activity->event_date]
        );

        return redirect()->route('admin.activities.index')
            ->with('success', "Actividad '{$activity->title}' programada exitosamente.");
    }

    /**
     * Show the form for editing the activity.
     */
    public function edit(int $id): View
    {
        $activity = Activity::withTrashed()->findOrFail($id);

        return view('admin.activities.edit', [
            'activity' => $activity,
        ]);
    }

    /**
     * Update the specified activity.
     */
    public function update(UpdateActivityRequest $request, int $id): RedirectResponse
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $activity->title = $validated['title'];
        $activity->category = $validated['category'];
        $activity->description = $validated['description'];
        $activity->location = $validated['location'] ?? null;
        $activity->event_date = $validated['event_date'] ?? null;
        $activity->status = $validated['status'];
        $activity->is_visible = $request->boolean('is_visible', true);
        $activity->save();

        AuditService::log(
            action: 'actualizar_actividad',
            entity: $activity,
            description: "Actividad comunitaria '{$activity->title}' actualizada.",
            properties: ['status' => $activity->status, 'is_visible' => $activity->is_visible]
        );

        return redirect()->route('admin.activities.index')
            ->with('success', "Actividad '{$activity->title}' actualizada correctamente.");
    }

    /**
     * Toggle visibility (is_visible) of the activity.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->is_visible = ! $activity->is_visible;
        $activity->save();

        $stateText = $activity->is_visible ? 'visible en el portal público' : 'oculta del portal público';

        AuditService::log(
            action: 'cambio_visibilidad_actividad',
            entity: $activity,
            description: "Estado de visibilidad de actividad '{$activity->title}' cambiado a {$stateText}.",
            properties: ['is_visible' => $activity->is_visible]
        );

        return back()->with('success', "La actividad ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the activity without destroying history.
     */
    public function destroy(Activity $activity): RedirectResponse
    {
        $title = $activity->title;
        $activity->delete();

        AuditService::log(
            action: 'inactivar_actividad',
            entity: $activity,
            description: "Actividad '{$title}' inactivada mediante SoftDelete (conservada en histórico).",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Actividad '{$title}' inactivada (resguardada en histórico con SoftDelete).");
    }

    /**
     * Restore a soft-deleted activity.
     */
    public function restore(int $id): RedirectResponse
    {
        $activity = Activity::onlyTrashed()->findOrFail($id);
        $activity->restore();

        AuditService::log(
            action: 'restaurar_actividad',
            entity: $activity,
            description: "Actividad '{$activity->title}' restaurada desde histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Actividad '{$activity->title}' restaurada y activa nuevamente.");
    }
}
