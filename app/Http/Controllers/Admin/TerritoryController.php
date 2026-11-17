<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTerritoryRequest;
use App\Http\Requests\Admin\UpdateTerritoryRequest;
use App\Models\Territory;
use App\Models\User;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TerritoryController extends Controller
{
    /**
     * Display a listing of territories and veredas.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = Territory::query()->with('creator');

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
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sector_badge', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('watersheds', 'like', "%{$search}%");
            });
        }

        $territories = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.territories.index', [
            'territories' => $territories,
            'search' => $search,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new territory.
     */
    public function create(): View
    {
        return view('admin.territories.create');
    }

    /**
     * Store a newly created territory.
     */
    public function store(StoreTerritoryRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $territory = new Territory;
        $territory->name = $validated['name'];
        $territory->slug = Str::slug($validated['name']);
        $territory->sector_badge = $validated['sector_badge'] ?? null;
        $territory->description = $validated['description'];
        $territory->estimated_families = $validated['estimated_families'] ?? null;
        $territory->watersheds = $validated['watersheds'] ?? null;
        $territory->main_activities = $validated['main_activities'] ?? null;
        $territory->image_url = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $territory->image_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'territories', 1600, 1000);
        }

        $territory->sort_order = $validated['sort_order'] ?? 0;
        $territory->is_visible = $request->boolean('is_visible', true);
        $territory->created_by = $user->id;
        $territory->save();

        AuditService::log(
            action: 'crear_territorio',
            entity: $territory,
            description: "Vereda / Territorio '{$territory->name}' registrado en el sistema.",
            properties: ['is_visible' => $territory->is_visible]
        );

        return redirect()->route('admin.territories.index')
            ->with('success', "Territorio '{$territory->name}' registrado exitosamente.");
    }

    /**
     * Show the form for editing the territory.
     */
    public function edit(int $id): View
    {
        $territory = Territory::withTrashed()->findOrFail($id);

        return view('admin.territories.edit', [
            'territory' => $territory,
        ]);
    }

    /**
     * Update the specified territory.
     */
    public function update(UpdateTerritoryRequest $request, int $id): RedirectResponse
    {
        $territory = Territory::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $territory->name = $validated['name'];
        $territory->sector_badge = $validated['sector_badge'] ?? null;
        $territory->description = $validated['description'];
        $territory->estimated_families = $validated['estimated_families'] ?? null;
        $territory->watersheds = $validated['watersheds'] ?? null;
        $territory->main_activities = $validated['main_activities'] ?? null;
        $territory->image_url = $validated['image_url'] ?? null;

        if ($request->hasFile('image_file')) {
            $territory->image_url = FileUploadService::optimizeAndStoreImage($request->file('image_file'), 'territories', 1600, 1000);
        }

        $territory->sort_order = $validated['sort_order'] ?? 0;
        $territory->is_visible = $request->boolean('is_visible', true);
        $territory->save();

        AuditService::log(
            action: 'actualizar_territorio',
            entity: $territory,
            description: "Territorio '{$territory->name}' actualizado.",
            properties: ['is_visible' => $territory->is_visible]
        );

        return redirect()->route('admin.territories.index')
            ->with('success', "Territorio '{$territory->name}' modificado correctamente.");
    }

    /**
     * Toggle visibility of the territory.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $territory = Territory::withTrashed()->findOrFail($id);
        $territory->is_visible = ! $territory->is_visible;
        $territory->save();

        $stateText = $territory->is_visible ? 'visible en el mapa y portal' : 'oculto del portal público';

        AuditService::log(
            action: 'cambio_visibilidad_territorio',
            entity: $territory,
            description: "Visibilidad de '{$territory->name}' cambiada a {$stateText}.",
            properties: ['is_visible' => $territory->is_visible]
        );

        return back()->with('success', "El territorio ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the territory.
     */
    public function destroy(Territory $territory): RedirectResponse
    {
        $name = $territory->name;
        $territory->delete();

        AuditService::log(
            action: 'inactivar_territorio',
            entity: $territory,
            description: "Territorio '{$name}' inactivado mediante SoftDelete.",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Territorio '{$name}' inactivado (resguardado con SoftDelete).");
    }

    /**
     * Restore a soft-deleted territory.
     */
    public function restore(int $id): RedirectResponse
    {
        $territory = Territory::onlyTrashed()->findOrFail($id);
        $territory->restore();

        AuditService::log(
            action: 'restaurar_territorio',
            entity: $territory,
            description: "Territorio '{$territory->name}' restaurado desde histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Territorio '{$territory->name}' restaurado exitosamente.");
    }
}
