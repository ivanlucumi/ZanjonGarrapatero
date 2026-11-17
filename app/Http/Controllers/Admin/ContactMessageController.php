<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of community contact inquiries and messages.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $status = $request->query('status', 'all');

        $query = ContactMessage::query()->with('attendedByUser');

        if ($status === 'trashed') {
            $query->onlyTrashed();
        } elseif ($status !== 'all') {
            $query->where('status', $status);
        } else {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search): void {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('territory', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $messages = $query->latest('id')->paginate(15)->withQueryString();

        $unreadCount = ContactMessage::query()->where('status', 'pendiente')->count();

        return view('admin.messages.index', [
            'messages' => $messages,
            'search' => $search,
            'status' => $status,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Display the specified contact message.
     */
    public function show(int $id): View
    {
        $message = ContactMessage::withTrashed()->findOrFail($id);

        return view('admin.messages.show', [
            'message' => $message,
        ]);
    }

    /**
     * Update status and internal notes for the inquiry.
     */
    public function update(UpdateContactMessageRequest $request, int $id): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();
        $message = ContactMessage::withTrashed()->findOrFail($id);
        $validated = $request->validated();

        $previousStatus = $message->status;
        $message->status = $validated['status'];
        $message->admin_notes = $validated['admin_notes'] ?? null;
        $message->attended_by = $user->id;
        $message->attended_at = now();
        $message->save();

        AuditService::log(
            action: 'gestionar_mensaje_contacto',
            entity: $message,
            description: "Mensaje de '{$message->name}' actualizado a estado '{$message->status}'.",
            properties: [
                'previous_status' => $previousStatus,
                'new_status' => $message->status,
                'attended_by' => $user->name,
            ]
        );

        return redirect()->route('admin.messages.show', $message->id)
            ->with('success', 'El estado del mensaje y las notas internas han sido actualizadas.');
    }

    /**
     * Soft delete (archive) the message.
     */
    public function destroy(ContactMessage $message): RedirectResponse
    {
        $name = $message->name;
        $message->delete();

        AuditService::log(
            action: 'inactivar_mensaje_contacto',
            entity: $message,
            description: "Mensaje de '{$name}' archivado con SoftDelete.",
            properties: ['deleted_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Mensaje de '{$name}' archivado (resguardado con SoftDelete).");
    }

    /**
     * Restore a soft-deleted message.
     */
    public function restore(int $id): RedirectResponse
    {
        $message = ContactMessage::onlyTrashed()->findOrFail($id);
        $message->restore();

        AuditService::log(
            action: 'restaurar_mensaje_contacto',
            entity: $message,
            description: "Mensaje de '{$message->name}' restaurado al buzón activo.",
            properties: ['restored_at' => now()->toDateTimeString()]
        );

        return back()->with('success', "Mensaje de '{$message->name}' restaurado al buzón activo.");
    }
}
