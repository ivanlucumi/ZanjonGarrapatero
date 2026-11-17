<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBoardMemberRequest;
use App\Http\Requests\Admin\UpdateBoardMemberRequest;
use App\Models\BoardMember;
use App\Models\User;
use App\Services\AuditService;
use App\Services\FileUploadService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BoardMemberController extends Controller
{
    /**
     * Display a listing of board members and traditional authorities.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');
        $committee = $request->query('committee');

        $query = BoardMember::query()->with('creator');

        if ($committee) {
            $query->where('committee', $committee);
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
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('role_title', 'like', "%{$search}%")
                    ->orWhere('committee', 'like', "%{$search}%")
                    ->orWhere('vereda', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(12)->withQueryString();

        return view('admin.board.index', [
            'members' => $members,
            'search' => $search,
            'status' => $status,
            'committee' => $committee,
        ]);
    }

    /**
     * Show the form for creating a new board member.
     */
    public function create(): View
    {
        return view('admin.board.create');
    }

    /**
     * Store a newly created board member.
     */
    public function store(StoreBoardMemberRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        $member = new BoardMember;
        $member->name = $validated['name'];
        $member->role_title = $validated['role_title'];
        $member->committee = $validated['committee'];
        $member->vereda = $validated['vereda'] ?? null;
        $member->bio = $validated['bio'] ?? null;
        $member->phone = $validated['phone'] ?? null;
        $member->email = $validated['email'] ?? null;
        $member->photo_url = $validated['photo_url'] ?? null;

        if ($request->hasFile('photo_file')) {
            $member->photo_url = FileUploadService::optimizeAndStoreImage($request->file('photo_file'), 'board', 800, 800);
        }

        $member->sort_order = $validated['sort_order'] ?? 0;
        $member->is_visible = $request->boolean('is_visible', true);
        $member->created_by = $user->id;
        $member->save();

        AuditService::log(
            action: 'crear_autoridad_directiva',
            entity: $member,
            description: "Directivo / Autoridad '{$member->name}' ({$member->role_title}) registrado.",
            properties: ['committee' => $member->committee, 'is_visible' => $member->is_visible]
        );

        return redirect()->route('admin.board.index')
            ->with('success', "Autoridad / Directivo '{$member->name}' registrado con éxito.");
    }

    /**
     * Show the form for editing the board member.
     */
    public function edit(int $id): View
    {
        $member = BoardMember::withTrashed()->findOrFail($id);

        return view('admin.board.edit', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified board member.
     */
    public function update(UpdateBoardMemberRequest $request, int $id): RedirectResponse
    {
        $member = BoardMember::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $member->name = $validated['name'];
        $member->role_title = $validated['role_title'];
        $member->committee = $validated['committee'];
        $member->vereda = $validated['vereda'] ?? null;
        $member->bio = $validated['bio'] ?? null;
        $member->phone = $validated['phone'] ?? null;
        $member->email = $validated['email'] ?? null;
        $member->photo_url = $validated['photo_url'] ?? null;

        if ($request->hasFile('photo_file')) {
            $member->photo_url = FileUploadService::optimizeAndStoreImage($request->file('photo_file'), 'board', 800, 800);
        }

        $member->sort_order = $validated['sort_order'] ?? 0;
        $member->is_visible = $request->boolean('is_visible', true);
        $member->save();

        AuditService::log(
            action: 'actualizar_autoridad_directiva',
            entity: $member,
            description: "Datos de directivo '{$member->name}' actualizados.",
            properties: ['is_visible' => $member->is_visible]
        );

        return redirect()->route('admin.board.index')
            ->with('success', "Información de '{$member->name}' modificada correctamente.");
    }

    /**
     * Toggle visibility of the board member.
     */
    public function toggleVisibility(int $id): RedirectResponse
    {
        $member = BoardMember::withTrashed()->findOrFail($id);
        $member->is_visible = ! $member->is_visible;
        $member->save();

        $stateText = $member->is_visible ? 'visible en el organigrama del portal' : 'oculto del portal público';

        AuditService::log(
            action: 'cambio_visibilidad_directivo',
            entity: $member,
            description: "Visibilidad de '{$member->name}' cambiada a {$stateText}.",
            properties: ['is_visible' => $member->is_visible]
        );

        return back()->with('success', "El directivo ahora está {$stateText}.");
    }

    /**
     * Soft delete (inactivate) the board member.
     */
    public function destroy(BoardMember $boardMember): RedirectResponse
    {
        $name = $boardMember->name;
        $boardMember->delete();

        AuditService::log(
            action: 'inactivar_autoridad_directiva',
            entity: $boardMember,
            description: "Directivo '{$name}' inactivado mediante SoftDelete.",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Directivo '{$name}' inactivado (resguardado con SoftDelete).");
    }

    /**
     * Restore a soft-deleted board member.
     */
    public function restore(int $id): RedirectResponse
    {
        $member = BoardMember::onlyTrashed()->findOrFail($id);
        $member->restore();

        AuditService::log(
            action: 'restaurar_autoridad_directiva',
            entity: $member,
            description: "Directivo '{$member->name}' restaurado desde histórico.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Directivo '{$member->name}' restaurado exitosamente.");
    }
}
