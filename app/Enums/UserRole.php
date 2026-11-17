<?php

namespace App\Enums;

enum UserRole: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Usuario = 'usuario';

    /**
     * Label visible to users in Spanish.
     */
    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Administrador',
            self::Admin => 'Administrador de Encuestas',
            self::Usuario => 'Comunero / Usuario',
        };
    }

    /**
     * Badge CSS helper for visual styling.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::SuperAdmin => 'badge-role-superadmin',
            self::Admin => 'badge-role-admin',
            self::Usuario => 'badge-role-usuario',
        };
    }

    /**
     * Short description of capabilities.
     */
    public function description(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Acceso total: gestión de usuarios, roles, auditoría, estadísticas globales y encuestas.',
            self::Admin => 'Gestión territorial: creación, administración y análisis de encuestas y censos.',
            self::Usuario => 'Participación comunitaria: diligenciamiento de censos, consulta de trámites y estado veredal.',
        };
    }

    /**
     * Icon representation (Bootstrap Icons).
     */
    public function icon(): string
    {
        return match ($this) {
            self::SuperAdmin => 'bi-shield-shaded',
            self::Admin => 'bi-clipboard2-data',
            self::Usuario => 'bi-person-check',
        };
    }

    /**
     * Determine if this role can manage users and system security.
     */
    public function canManageUsers(): bool
    {
        return $this === self::SuperAdmin;
    }

    /**
     * Determine if this role can create, edit and analyze surveys.
     */
    public function canManageSurveys(): bool
    {
        return $this === self::SuperAdmin || $this === self::Admin;
    }

    /**
     * Determine if this role can manage portal CMS content (publications, activities, media).
     */
    public function canManageContent(): bool
    {
        return $this === self::SuperAdmin || $this === self::Admin;
    }

    /**
     * Determine if this role can view audit trails and login access history.
     */
    public function canViewAudit(): bool
    {
        return $this === self::SuperAdmin;
    }

    /**
     * Determine if this role has full overview access across all metrics.
     */
    public function canViewAll(): bool
    {
        return $this === self::SuperAdmin;
    }
}
