@extends('layouts.admin')

@section('title', 'Redactar Correo Institucional')

@section('content')
<div class="container-fluid px-0" style="max-width: 940px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-uppercase fw-bold small" style="color: #8d6e63; letter-spacing: 0.1em;">Centro de Correos</span>
            <h2 class="h3 fw-bold mb-0" style="color: #3e2723;">Nuevo Envío de Correo</h2>
        </div>
        <a href="{{ route('admin.emails.index') }}" class="btn btn-admin-outline">
            <i class="bi bi-arrow-left me-1"></i> Volver al historial
        </a>
    </div>

    <form action="{{ route('admin.emails.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <x-admin.form-card title="Tipo de Envío y Destinatarios" icon="bi-people-fill" kicker="01 · Segmentación">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label fw-bold small text-dark">Modo de Envío <span class="text-danger">*</span></label>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="d-flex align-items-center gap-2 p-3 rounded-3 border bg-white cursor-pointer h-100">
                                <input type="radio" name="send_type" value="individual" class="form-check-input mt-0" {{ old('send_type', 'individual') === 'individual' ? 'checked' : '' }}>
                                <div>
                                    <strong class="d-block small text-dark"><i class="bi bi-person-check-fill me-1"></i> Selección Manual</strong>
                                    <span class="text-muted" style="font-size: 0.72rem;">Buscar y elegir 1 o más personas</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="d-flex align-items-center gap-2 p-3 rounded-3 border bg-white cursor-pointer h-100">
                                <input type="radio" name="send_type" value="role" class="form-check-input mt-0" {{ old('send_type') === 'role' ? 'checked' : '' }}>
                                <div>
                                    <strong class="d-block small text-dark"><i class="bi bi-diagram-3-fill me-1"></i> Por Rol</strong>
                                    <span class="text-muted" style="font-size: 0.72rem;">Comuneros, Admins o Super</span>
                                </div>
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label class="d-flex align-items-center gap-2 p-3 rounded-3 border bg-white cursor-pointer h-100">
                                <input type="radio" name="send_type" value="massive" class="form-check-input mt-0" {{ old('send_type') === 'massive' ? 'checked' : '' }}>
                                <div>
                                    <strong class="d-block small text-dark"><i class="bi bi-broadcast me-1"></i> Masivo</strong>
                                    <span class="text-muted" style="font-size: 0.72rem;">Todos ({{ $totalUsersCount }} inscritos)</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Buscador y Selector Múltiple de Personas --}}
                <div class="col-12" id="wrap-individual">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <label for="recipientSearch" class="form-label fw-bold small text-dark mb-0">
                            Buscar y Seleccionar Destinatarios <span class="text-danger">*</span>
                            <span id="selectedCountBadge" class="badge bg-secondary ms-1">0 seleccionados</span>
                        </label>
                        <div class="d-flex gap-2">
                            <button type="button" id="btnSelectVisible" class="btn btn-sm btn-admin-outline py-0 px-2" style="font-size: 0.74rem;">Seleccionar visibles</button>
                            <button type="button" id="btnClearSelection" class="btn btn-sm btn-outline-secondary py-0 px-2" style="font-size: 0.74rem;">Limpiar</button>
                        </div>
                    </div>

                    <div class="input-group mb-2">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="recipientSearch" class="form-control" placeholder="Buscar por nombre, correo o vereda...">
                    </div>

                    <div id="recipientChecklist" class="border rounded-3 p-2 bg-white" style="max-height: 240px; overflow-y: auto;">
                        @php $oldIds = collect(old('recipient_user_ids', []))->map(fn ($id) => (int) $id)->all(); @endphp
                        @foreach($users as $u)
                            <label class="recipient-row d-flex align-items-center justify-content-between gap-2 p-2 rounded-2 border-bottom" data-search="{{ strtolower($u->name.' '.$u->email.' '.($u->vereda ?? '').' '.$u->role->label()) }}" style="cursor: pointer;">
                                <div class="d-flex align-items-center gap-2 overflow-hidden">
                                    <input type="checkbox" name="recipient_user_ids[]" value="{{ $u->id }}" class="form-check-input recipient-checkbox mt-0" {{ in_array($u->id, $oldIds, true) ? 'checked' : '' }}>
                                    <div class="overflow-hidden">
                                        <strong class="d-block small text-dark text-truncate">{{ $u->name }}</strong>
                                        <span class="small text-muted text-truncate d-block" style="font-size: 0.75rem;">{{ $u->email }} @if($u->vereda) &bull; {{ $u->vereda }} @endif</span>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border flex-shrink-0" style="font-size: 0.68rem;">{{ $u->role->label() }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('recipient_user_ids')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-none" id="wrap-role">
                    <label for="target_role" class="form-label fw-bold small text-dark">Seleccionar Rol Destinatario <span class="text-danger">*</span></label>
                    <select name="target_role" id="target_role" class="form-select rounded-3">
                        <option value="">-- Selecciona un rol --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ old('target_role') === $role->value ? 'selected' : '' }}>
                                {{ $role->label() }} ({{ $roleCounts[$role->value] ?? 0 }} inscritos)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 d-none" id="wrap-massive">
                    <div class="p-3 rounded-3 border" style="background: #faf7f5; border-color: rgba(109, 76, 61, 0.22) !important;">
                        <strong class="small text-dark d-block"><i class="bi bi-info-circle-fill me-1 text-warning"></i> Envío Masivo Activado</strong>
                        <span class="small text-muted">El comunicado será enviado síncronamente con reintentos automáticos a las <strong>{{ $totalUsersCount }}</strong> personas registradas en el portal.</span>
                    </div>
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.form-card title="Contenido y Archivos Adjuntos" icon="bi-envelope-fill" kicker="02 · Mensaje & Documentos">
            <div class="row g-3">
                <div class="col-12">
                    <x-admin.input name="subject" label="Asunto del Correo" icon="bi-chat-left-text" placeholder="Ej. Convocatoria Asamblea General Territorial" required="true" />
                </div>
                <div class="col-12">
                    <label for="body" class="form-label fw-bold small text-dark">Mensaje <span class="text-danger">*</span></label>
                    <textarea name="body" id="body" rows="6" class="form-control rounded-3" placeholder="Redacta aquí el comunicado oficial para los destinatarios..." required>{{ old('body') }}</textarea>
                </div>
                <div class="col-12">
                    <label for="attachments" class="form-label fw-bold small text-dark">
                        Adjuntar Documentos, Imágenes u Otros Archivos (Opcional)
                    </label>
                    <input type="file" name="attachments[]" id="attachments" class="form-control rounded-3" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.jpg,.jpeg,.png,.webp,.mp4">
                    <div class="form-text small text-muted">
                        <i class="bi bi-paperclip me-1"></i>
                        Si el peso total es hasta <strong>10 MB</strong> se adjuntan directamente al correo; si supera <strong>10 MB</strong> se generan automáticamente <strong>enlaces de descarga directa</strong> dentro del correo.
                    </div>
                </div>
            </div>
        </x-admin.form-card>

        <x-admin.actions cancelUrl="{{ route('admin.emails.index') }}" submitText="Enviar Correo Ahora" icon="bi-send-fill" />
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="send_type"]');
    const wrapIndividual = document.getElementById('wrap-individual');
    const wrapRole = document.getElementById('wrap-role');
    const wrapMassive = document.getElementById('wrap-massive');

    const updateVisibility = () => {
        const selected = document.querySelector('input[name="send_type"]:checked')?.value || 'individual';
        wrapIndividual.classList.toggle('d-none', selected !== 'individual');
        wrapRole.classList.toggle('d-none', selected !== 'role');
        wrapMassive.classList.toggle('d-none', selected !== 'massive');
    };

    radios.forEach(r => r.addEventListener('change', updateVisibility));
    updateVisibility();

    // Buscador de destinatarios y contador múltiple
    const searchInput = document.getElementById('recipientSearch');
    const rows = document.querySelectorAll('.recipient-row');
    const checkboxes = document.querySelectorAll('.recipient-checkbox');
    const countBadge = document.getElementById('selectedCountBadge');

    const updateCount = () => {
        const checkedCount = document.querySelectorAll('.recipient-checkbox:checked').length;
        if (countBadge) {
            countBadge.textContent = `${checkedCount} seleccionado(s)`;
        }
    };

    searchInput?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        rows.forEach(row => {
            const matches = !q || (row.dataset.search || '').includes(q);
            row.classList.toggle('d-none', !matches);
        });
    });

    checkboxes.forEach(cb => cb.addEventListener('change', updateCount));

    document.getElementById('btnSelectVisible')?.addEventListener('click', () => {
        rows.forEach(row => {
            if (!row.classList.contains('d-none')) {
                const cb = row.querySelector('.recipient-checkbox');
                if (cb) cb.checked = true;
            }
        });
        updateCount();
    });

    document.getElementById('btnClearSelection')?.addEventListener('click', () => {
        checkboxes.forEach(cb => { cb.checked = false; });
        updateCount();
    });

    updateCount();
});
</script>
@endpush
