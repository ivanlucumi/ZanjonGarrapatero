<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SwitchRoleRequest;
use App\Http\Requests\Admin\UpdateUserRoleRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * List all users (Super Admin only).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $roleFilter = $request->query('role');

        $users = User::query()
            ->when($search, function ($query, $search): void {
                $query->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('document_number', 'like', "%{$search}%")
                        ->orWhere('vereda', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $roleFilter): void {
                $query->where('role', $roleFilter);
            })
            ->withCount('surveyResponses')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'currentFilter' => $roleFilter,
            'search' => $search,
        ]);
    }

    /**
     * Update user role and territorial profile (Super Admin only).
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $user->update([
            'role' => $validated['role'],
            'vereda' => $validated['vereda'] ?? $user->vereda,
            'phone' => $validated['phone'] ?? $user->phone,
            'document_number' => $validated['document_number'] ?? $user->document_number,
        ]);

        return back()->with('success', "Perfil y permisos de '{$user->name}' actualizados a {$user->role->label()}.");
    }

    /**
     * Demo role switcher: allows the authenticated user to switch role to test all 3 views.
     */
    public function switchDemoRole(SwitchRoleRequest $request): RedirectResponse
    {
        /** @var User $currentUser */
        $currentUser = $request->user();

        $targetRole = UserRole::from($request->validated('role'));
        $currentUser->update(['role' => $targetRole]);

        return redirect()->route('admin.dashboard')->with('success', "Modo demostración activado: ahora estás viendo la plataforma como '{$targetRole->label()}'.");
    }
}
