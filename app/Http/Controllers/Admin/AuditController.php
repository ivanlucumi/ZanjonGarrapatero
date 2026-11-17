<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\LoginLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of system audit trail logs (trazabilidad y cambios).
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $action = $request->query('action');
        $entityType = $request->query('entity_type');

        $query = AuditLog::query()->with('user');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('user_name', 'like', "%{$search}%")
                    ->orWhere('entity_title', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        if ($entityType) {
            $query->where('entity_type', $entityType);
        }

        $logs = $query->latest('id')->paginate(20)->withQueryString();

        $actionTypes = AuditLog::query()
            ->select('action')
            ->distinct()
            ->pluck('action')
            ->sort()
            ->values();

        $entityTypes = AuditLog::query()
            ->select('entity_type')
            ->distinct()
            ->whereNotNull('entity_type')
            ->pluck('entity_type')
            ->sort()
            ->values();

        return view('admin.audit.index', [
            'logs' => $logs,
            'search' => $search,
            'action' => $action,
            'entityType' => $entityType,
            'actionTypes' => $actionTypes,
            'entityTypes' => $entityTypes,
        ]);
    }

    /**
     * Display the login access log history (inicios de sesión, IPs, dispositivos).
     */
    public function logins(Request $request): View
    {
        $search = $request->query('search');
        $role = $request->query('role');

        $query = LoginLog::query()->with('user');

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('user_email', 'like', "%{$search}%")
                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('user_agent', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $logins = $query->latest('id')->paginate(20)->withQueryString();

        return view('admin.audit.logins', [
            'logins' => $logins,
            'search' => $search,
            'role' => $role,
        ]);
    }
}
