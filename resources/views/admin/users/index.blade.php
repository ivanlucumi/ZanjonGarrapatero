@extends('layouts.admin')

@section('title', 'Gestión de Comuneros y Asignación de Roles')

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <div>
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge-role badge-role-superadmin" style="font-size: 0.72rem; padding: 3px 10px;">
                <i class="bi bi-shield-shaded"></i> Módulo Exclusivo
            </span>
            <span class="text-muted small">&bull; Super Administrador ("Ve Todo")</span>
        </div>
        <h2 class="h3 fw-bold text-dark mb-1">Censo de Comuneros & Control de Privilegios</h2>
        <p class="text-muted small mb-0">
            Administra los roles de acceso (Super Admin, Admin Encuestas, Comunero/Usuario) y la información territorial de cada integrante.
        </p>
    </div>
</div>

<!-- FILTROS Y BÚSQUEDA -->
<div class="admin-card mb-4">
    <div class="admin-card-body py-3">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Buscar por nombre, correo, cédula o vereda..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-12 col-md-4">
                <select name="role" class="form-select">
                    <option value="">Todos los roles...</option>
                    @foreach($roles as $r)
                        <option value="{{ $r->value }}" {{ $currentFilter === $r->value ? 'selected' : '' }}>
                            {{ $r->label() }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-admin-primary flex-grow-1">Filtrar</button>
                @if($search || $currentFilter)
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- TABLA DE COMUNEROS -->
<div class="admin-card">
    <div class="admin-card-header">
        <h3 class="admin-card-title">
            <i class="bi bi-people-fill text-primary"></i>
            Listado de Usuarios Registrados ({{ $users->total() }})
        </h3>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive px-3 py-2">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre y Correo</th>
                        <th>Rol Asignado</th>
                        <th>Vereda</th>
                        <th>Cédula / Teléfono</th>
                        <th>Censos Diligenciados</th>
                        <th class="text-end">Modificar Rol</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $u->name }}</div>
                                <div class="small text-muted">{{ $u->email }}</div>
                            </td>
                            <td>
                                <span class="badge-role {{ $u->role->badgeClass() }}" style="font-size: 0.72rem; padding: 4px 10px;">
                                    <i class="bi {{ $u->role->icon() }}"></i> {{ $u->role->label() }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $u->vereda ?? 'Sin asignar' }}</span>
                            </td>
                            <td>
                                <div class="small text-dark">Doc: {{ $u->document_number ?? 'S/D' }}</div>
                                <div class="small text-muted">Tel: {{ $u->phone ?? 'S/D' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis border">
                                    {{ $u->survey_responses_count }} encuestas
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-admin-outline" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}">
                                    <i class="bi bi-pencil-square"></i> Cambiar Rol
                                </button>

                                <!-- Modal de Edición de Usuario / Rol -->
                                <div class="modal fade text-start" id="editUserModal{{ $u->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $u->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content rounded-4 border-0 shadow-lg">
                                            <form action="{{ route('admin.users.update-role', $u) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-header border-bottom">
                                                    <h5 class="modal-title fw-bold text-dark" id="modalLabel{{ $u->id }}">
                                                        Editar Perfil: {{ $u->name }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-dark">Rol del Usuario <span class="text-danger">*</span></label>
                                                        <select name="role" class="form-select rounded-3" required>
                                                            @foreach($roles as $roleOption)
                                                                <option value="{{ $roleOption->value }}" {{ $u->role === $roleOption ? 'selected' : '' }}>
                                                                    {{ $roleOption->label() }} — {{ $roleOption->value === 'super_admin' ? 'Ve todo' : ($roleOption->value === 'admin' ? 'Encuestas' : 'Comunero') }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text small">
                                                            <strong>Super Admin:</strong> ve todo y gestiona usuarios.<br>
                                                            <strong>Admin:</strong> gestiona encuestas y censos territoriales.<br>
                                                            <strong>Usuario:</strong> responde censos y consulta ficha comunitaria.
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-dark">Vereda Territorial</label>
                                                        <select name="vereda" class="form-select rounded-3">
                                                            <option value="Zanjón de Garrapatero" {{ $u->vereda === 'Zanjón de Garrapatero' ? 'selected' : '' }}>Zanjón de Garrapatero</option>
                                                            <option value="San Antonio" {{ $u->vereda === 'San Antonio' ? 'selected' : '' }}>San Antonio</option>
                                                            <option value="Dominguillo" {{ $u->vereda === 'Dominguillo' ? 'selected' : '' }}>Dominguillo</option>
                                                            <option value="La Toma" {{ $u->vereda === 'La Toma' ? 'selected' : '' }}>La Toma</option>
                                                            <option value="El Palmar" {{ $u->vereda === 'El Palmar' ? 'selected' : '' }}>El Palmar</option>
                                                        </select>
                                                    </div>

                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold small text-dark">Cédula</label>
                                                            <input type="text" name="document_number" class="form-control rounded-3" value="{{ $u->document_number }}">
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label fw-bold small text-dark">Teléfono</label>
                                                            <input type="text" name="phone" class="form-control rounded-3" value="{{ $u->phone }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer border-top">
                                                    <button type="button" class="btn btn-outline-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancelar</button>
                                                    <button type="submit" class="btn-admin-gold px-4">
                                                        Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No se encontraron usuarios con los criterios de búsqueda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
