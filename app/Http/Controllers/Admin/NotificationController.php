<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    /**
     * Return pending contact requests and email notifications for admins.
     */
    public function unread(): JsonResponse
    {
        $pendingQuery = ContactMessage::query()->where('status', 'pendiente');

        $count = $pendingQuery->count();
        $latest = $pendingQuery->latest('id')
            ->take(6)
            ->get(['id', 'name', 'email', 'territory', 'message', 'created_at'])
            ->map(function (ContactMessage $msg): array {
                return [
                    'id' => $msg->id,
                    'name' => $msg->name,
                    'email' => $msg->email,
                    'territory' => $msg->territory ?? 'Territorio General',
                    'preview' => str($msg->message)->limit(70)->toString(),
                    'time_ago' => $msg->created_at?->diffForHumans() ?? 'Reciente',
                    'url' => route('admin.messages.show', $msg->id),
                ];
            });

        return response()->json([
            'unread_count' => $count,
            'latest_id' => $latest->first()['id'] ?? 0,
            'notifications' => $latest,
        ]);
    }
}
