<!-- SweetAlert2 Library & Custom Theme Integration -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    div.swal2-container .swal2-popup {
        border-radius: 20px !important;
        border: 1px solid rgba(181, 138, 69, 0.35) !important;
        box-shadow: 0 20px 60px rgba(23, 45, 77, 0.22) !important;
        font-family: 'Outfit', 'Inter', system-ui, sans-serif !important;
        background: #ffffff !important;
        color: #172d4d !important;
    }
    div.swal2-container .swal2-title {
        color: #172d4d !important;
        font-weight: 700 !important;
        font-size: 1.35rem !important;
        letter-spacing: -0.01em !important;
    }
    div.swal2-container .swal2-html-container {
        color: #475569 !important;
        font-size: 0.95rem !important;
        line-height: 1.6 !important;
    }
    div.swal2-container .swal2-confirm {
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 10px 24px !important;
        box-shadow: 0 4px 14px rgba(23, 45, 77, 0.2) !important;
        border: none !important;
    }
    div.swal2-container .swal2-cancel {
        border-radius: 12px !important;
        font-weight: 600 !important;
        font-size: 0.9rem !important;
        padding: 10px 20px !important;
        background-color: #64748b !important;
        color: #ffffff !important;
        border: none !important;
    }
    div.swal2-container .swal2-icon.swal2-success {
        border-color: #b58a45 !important;
        color: #b58a45 !important;
    }
    div.swal2-container .swal2-icon.swal2-success .swal2-success-ring {
        border-color: rgba(181, 138, 69, 0.3) !important;
    }
    div.swal2-container .swal2-icon.swal2-success [class^='swal2-success-line'] {
        background-color: #b58a45 !important;
    }
    div.swal2-container .swal2-timer-progress-bar {
        background: var(--admin-gold, #b58a45) !important;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Notificación de Éxito desde sesión Flash
    @if(session('success') || session('status_success'))
        Swal.fire({
            icon: 'success',
            title: '¡Operación Exitosa!',
            text: @json(session('success') ?? session('status_success')),
            confirmButtonColor: '#172D4D',
            timer: 4500,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    @endif

    // 2. Notificación de Estado / Info
    @if(session('status') && !session('success') && !session('status_success'))
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: @json(session('status')),
            confirmButtonColor: '#172D4D',
            timer: 4000,
            timerProgressBar: true,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    @endif

    // 3. Notificación de Error
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Atención',
            text: @json(session('error')),
            confirmButtonColor: '#172D4D',
            confirmButtonText: 'Entendido'
        });
    @endif

    // 4. Errores de Validación de Formularios
    @if($errors->any())
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos pendientes',
            html: `
                <div style="text-align: left; padding: 0 10px;">
                    <p style="margin-bottom: 8px; font-weight: 600;">Por favor verifica los siguientes campos:</p>
                    <ul style="margin: 0; padding-left: 20px; font-size: 0.88rem; color: #dc3545;">
                        @foreach($errors->all() as $error)
                            <li style="margin-bottom: 4px;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `,
            confirmButtonColor: '#172D4D',
            confirmButtonText: '<i class="bi bi-pencil-square me-1"></i> Corregir campos'
        });
    @endif

    // 5. Interceptor Global de Confirmación SweetAlert para SoftDeletes e Inactivaciones
    document.addEventListener('submit', function (e) {
        const form = e.target;

        // Si tiene la clase de confirmación SweetAlert activa
        if (form.classList.contains('js-confirm-delete')) {
            e.preventDefault();

            const title = form.dataset.confirmTitle || '¿Inactivar este registro?';
            const text = form.dataset.confirmText || 'Esta acción resguarda el elemento en el histórico mediante SoftDelete. Podrás restaurarlo en cualquier momento.';
            const confirmBtn = form.dataset.confirmBtn || '<i class="bi bi-archive-fill me-1"></i> Sí, inactivar';

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#172D4D',
                cancelButtonColor: '#64748b',
                confirmButtonText: confirmBtn,
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.classList.remove('js-confirm-delete');
                    form.submit();
                }
            });
        }
    });

    // 6. Vista previa reactiva para Dropzones interactivos
    document.querySelectorAll('.admin-dropzone-input').forEach(input => {
        input.addEventListener('change', function () {
            const container = this.closest('.admin-dropzone-box');
            const preview = container?.querySelector('.admin-dropzone-preview');
            const filenameSpan = container?.querySelector('.dz-filename');

            if (this.files && this.files[0]) {
                const file = this.files[0];
                const sizeKb = (file.size / 1024).toFixed(1);
                const sizeFormatted = sizeKb > 1024 ? (sizeKb / 1024).toFixed(2) + ' MB' : sizeKb + ' KB';

                if (filenameSpan) {
                    filenameSpan.textContent = `${file.name} (${sizeFormatted})`;
                }
                if (preview) {
                    preview.style.display = 'inline-flex';
                }
            }
        });
    });
});
</script>
