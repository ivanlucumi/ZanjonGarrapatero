<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    /**
     * Record an administrative audit action.
     *
     * @param  array<string, mixed>|null  $properties
     */
    public static function log(
        string $action,
        mixed $entity,
        string $description,
        ?array $properties = null,
        ?User $user = null
    ): AuditLog {
        /** @var User|null $currentUser */
        $currentUser = $user ?? Auth::user();

        $userName = $currentUser ? $currentUser->name : 'Sistema Autónomo';
        $userRole = $currentUser ? ($currentUser->role?->label() ?? 'Usuario') : 'Sistema';
        $userId = $currentUser?->id;

        $entityType = is_object($entity) ? class_basename($entity) : (string) $entity;
        $entityId = ($entity instanceof Model) ? $entity->getKey() : null;
        $entityTitle = null;

        if ($entity instanceof Model) {
            $entityTitle = $entity->title ?? $entity->name ?? (string) $entityId;
        } elseif (is_string($entity)) {
            $entityTitle = $entity;
        }

        return AuditLog::query()->create([
            'user_id' => $userId,
            'user_name' => $userName,
            'user_role' => $userRole,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'entity_title' => $entityTitle,
            'description' => $description,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'user_agent' => Request::userAgent() ?? 'Consola / Local',
            'properties' => $properties,
            'created_at' => now(),
        ]);
    }
}
