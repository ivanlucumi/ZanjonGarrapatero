<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Panel Administrativo') — Consejo Comunitario Zanjón de Garrapatero</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Theme & Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/theme-colors.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-layout.css') }}">
    @stack('styles')
</head>
<body class="admin-body">
    <!-- Ambient Glassmorphism Refractive Glow Layer -->
    <div class="admin-ambient" aria-hidden="true">
        <div class="admin-ambient-orb admin-ambient-gold"></div>
        <div class="admin-ambient-orb admin-ambient-blue"></div>
        <div class="admin-ambient-orb admin-ambient-pearl"></div>
    </div>

    <!-- CONTENEDOR PRINCIPAL CON MENÚ LATERAL IZQUIERDO -->
    <div class="admin-wrapper">
        <!-- Barra Superior Móvil (< 992px) -->
        <header class="admin-mobile-topbar d-lg-none" aria-label="Navegación móvil del panel">
            <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-white text-decoration-none">
                <img src="{{ asset('logo/logo.webp') }}" alt="Logo CCZG" width="36" height="36" class="brand-mark-mini">
                <div>
                    <strong class="d-block text-white fs-6 leading-tight">Zanjón de Garrapatero</strong>
                    <small style="color: var(--admin-gold); font-size: 0.65rem; letter-spacing: 0.1em; text-transform: uppercase;">Gobierno Comunitario</small>
                </div>
            </a>
            <button class="btn btn-outline-light border-0 p-2" type="button" id="adminSidebarToggle" aria-label="Abrir menú lateral">
                <i class="bi bi-list fs-2" style="color: var(--admin-gold);"></i>
            </button>
        </header>

        <!-- Telón de fondo para versión móvil -->
        <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop" aria-hidden="true"></div>

        <!-- MENÚ LATERAL IZQUIERDO (SIDEBAR ADMINISTRATIVO ESCALABLE) -->
        <aside class="admin-sidebar" id="adminSidebar" aria-label="Menú lateral de administración">
            <!-- Encabezado del Menú con Logo Institucional -->
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand">
                    <img src="{{ asset('logo/logo.webp') }}" alt="Logo del Consejo Comunitario" width="46" height="46">
                    <div>
                        <h1 class="admin-sidebar-brand-title">Zanjón de Garrapatero</h1>
                        <span class="admin-sidebar-brand-sub">Panel de Gobierno</span>
                    </div>
                </a>
            </div>

            <!-- Ficha del Usuario Autenticado en el Menú -->
            @auth
            <div class="admin-sidebar-profile">
                <div class="d-flex align-items-center gap-3">
                    <div class="admin-sidebar-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="admin-sidebar-user-name text-truncate">{{ Auth::user()->name }}</div>
                        <div class="admin-sidebar-user-vereda text-truncate">
                            <i class="bi bi-geo-alt-fill text-warning me-1"></i>{{ Auth::user()->vereda ?? 'Zanjón de Garrapatero' }}
                        </div>
                    </div>
                </div>

                <div class="admin-sidebar-role-badge-row">
                    <span class="badge-role {{ Auth::user()->role->badgeClass() }}" style="font-size: 0.72rem; padding: 4px 10px;">
                        <i class="bi {{ Auth::user()->role->icon() }}"></i> {{ Auth::user()->role->label() }}
                    </span>
                </div>
            </div>

            <!-- Selector de Simulación de Roles (Para alternar entre los 3 roles fácilmente en la demo) -->
            <div class="admin-sidebar-section-header">
                <i class="bi bi-person-gear text-warning me-1"></i> Simular Rol (Demo):
            </div>
            <div class="admin-role-switcher-grid">
                <form action="{{ route('admin.demo.switch-role') }}" method="POST" class="d-block w-100">
                    @csrf
                    <input type="hidden" name="role" value="super_admin">
                    <button type="submit" class="admin-sidebar-role-btn {{ Auth::user()->isSuperAdmin() ? 'active' : '' }}" title="Super Admin: Ve todo y gestiona usuarios">
                        <i class="bi bi-shield-shaded"></i> Super Admin
                    </button>
                </form>

                <form action="{{ route('admin.demo.switch-role') }}" method="POST" class="d-block w-100">
                    @csrf
                    <input type="hidden" name="role" value="admin">
                    <button type="submit" class="admin-sidebar-role-btn {{ Auth::user()->isAdmin() ? 'active' : '' }}" title="Admin: Gestión de encuestas y censos">
                        <i class="bi bi-clipboard2-data"></i> Admin Encuestas
                    </button>
                </form>

                <form action="{{ route('admin.demo.switch-role') }}" method="POST" class="d-block w-100">
                    @csrf
                    <input type="hidden" name="role" value="usuario">
                    <button type="submit" class="admin-sidebar-role-btn {{ Auth::user()->isUsuario() ? 'active' : '' }}" title="Comunero: Diligencia censos y consulta ficha">
                        <i class="bi bi-person-check"></i> Comunero
                    </button>
                </form>
            </div>
            @endauth

            <!-- NAVEGACIÓN MODULAR Y EXPANDIBLE DEL MENÚ LATERAL -->
            <nav class="admin-sidebar-nav">
                <!-- Sección 1: Principal -->
                <div class="admin-sidebar-section-header">Principal</div>
                <ul class="admin-nav-list">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2"></i>
                            <span>Panel de Control</span>
                        </a>
                    </li>
                </ul>

                <!-- Sección 2: Censo y Consultas -->
                <div class="admin-sidebar-section-header">Censo y Consultas</div>
                <ul class="admin-nav-list">
                    <li>
                        <a href="{{ route('admin.surveys.index') }}" class="admin-nav-item {{ request()->routeIs('admin.surveys.index') || request()->routeIs('admin.surveys.show') || request()->routeIs('admin.surveys.respond') || request()->routeIs('admin.surveys.responses') ? 'active' : '' }}">
                            <i class="bi bi-clipboard2-check"></i>
                            <span>Encuestas y Censos</span>
                        </a>
                    </li>
                    @if(Auth::user()->role->canManageSurveys())
                        <li>
                            <a href="{{ route('admin.surveys.create') }}" class="admin-nav-item {{ request()->routeIs('admin.surveys.create') ? 'active' : '' }}">
                                <i class="bi bi-plus-circle"></i>
                                <span>Crear Encuesta</span>
                                <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.65rem;">Gestión</span>
                            </a>
                        </li>
                    @endif
                </ul>

                <!-- Sección 3: Gestión de Contenidos (CMS del Portal) -->
                <div class="admin-sidebar-section-header">Administración Web (CMS)</div>
                <ul class="admin-nav-list">
                    @if(Auth::user()->role->canManageContent())
                        <li>
                            <a href="{{ route('admin.carousel.index') }}" class="admin-nav-item {{ request()->routeIs('admin.carousel.*') ? 'active' : '' }}">
                                <i class="bi bi-sliders"></i>
                                <span>Carrusel Principal</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.publications.index') }}" class="admin-nav-item {{ request()->routeIs('admin.publications.*') ? 'active' : '' }}">
                                <i class="bi bi-newspaper"></i>
                                <span>Noticias y Actualidad</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.activities.index') }}" class="admin-nav-item {{ request()->routeIs('admin.activities.*') ? 'active' : '' }}">
                                <i class="bi bi-calendar3"></i>
                                <span>Agenda y Actividades</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.media.index') }}" class="admin-nav-item {{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                                <i class="bi bi-collection-play"></i>
                                <span>Galería y Videos</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.territories.index') }}" class="admin-nav-item {{ request()->routeIs('admin.territories.*') ? 'active' : '' }}">
                                <i class="bi bi-geo-alt"></i>
                                <span>Veredas y Territorio</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.board.index') }}" class="admin-nav-item {{ request()->routeIs('admin.board.*') ? 'active' : '' }}">
                                <i class="bi bi-diagram-3"></i>
                                <span>Autoridades (Nosotros)</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.documents.index') }}" class="admin-nav-item {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                                <span>Documentos y Archivo</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.messages.index') }}" class="admin-nav-item {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">
                                <i class="bi bi-inbox-fill"></i>
                                <span>Buzón de Contactos</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="admin-nav-item disabled" title="Requiere rol de Administrador o Super Administrador">
                                <i class="bi bi-lock-fill"></i>
                                <span>Contenidos y Portal</span>
                                <i class="bi bi-shield-lock ms-auto small text-warning"></i>
                            </span>
                        </li>
                    @endif
                </ul>

                <!-- Sección 4: Gobierno, Comuneros y Auditoría -->
                <div class="admin-sidebar-section-header">Gobierno y Seguridad</div>
                <ul class="admin-nav-list">
                    @if(Auth::user()->isSuperAdmin())
                        <li>
                            <a href="{{ route('admin.users.index') }}" class="admin-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="bi bi-people-fill"></i>
                                <span>Comuneros y Roles</span>
                                <span class="badge bg-warning text-dark ms-auto" style="font-size: 0.65rem;">Super Admin</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.audit.index') }}" class="admin-nav-item {{ request()->routeIs('admin.audit.index') ? 'active' : '' }}">
                                <i class="bi bi-clock-history"></i>
                                <span>Trazabilidad de Cambios</span>
                                <span class="badge bg-info text-dark ms-auto" style="font-size: 0.65rem;">Auditoría</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.audit.logins') }}" class="admin-nav-item {{ request()->routeIs('admin.audit.logins') ? 'active' : '' }}">
                                <i class="bi bi-fingerprint"></i>
                                <span>Inicios de Sesión (IPs)</span>
                                <span class="badge bg-secondary ms-auto" style="font-size: 0.65rem;">Acceso</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="admin-nav-item disabled" title="Módulo exclusivo para Super Administrador">
                                <i class="bi bi-lock-fill"></i>
                                <span>Comuneros y Roles</span>
                                <i class="bi bi-shield-lock ms-auto small text-warning"></i>
                            </span>
                        </li>
                        <li>
                            <span class="admin-nav-item disabled" title="Auditoría exclusiva para Super Administrador">
                                <i class="bi bi-lock-fill"></i>
                                <span>Auditoría y Sesiones</span>
                                <i class="bi bi-shield-lock ms-auto small text-warning"></i>
                            </span>
                        </li>
                    @endif
                </ul>

                <!-- Sección 4: Portales Públicos -->
                <div class="admin-sidebar-section-header">Portales Web Públicos</div>
                <ul class="admin-nav-list">
                    <li>
                        <a href="{{ route('index') }}" target="_blank" class="admin-nav-item">
                            <i class="bi bi-layout-text-window-reverse"></i>
                            <span>Portal (Diseño 1 Superior)</span>
                            <i class="bi bi-box-arrow-up-right ms-auto small text-white-50"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('index2') }}" target="_blank" class="admin-nav-item">
                            <i class="bi bi-layout-sidebar-reverse"></i>
                            <span>Portal (Diseño 2 Lateral)</span>
                            <i class="bi bi-box-arrow-up-right ms-auto small text-white-50"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Pie del Menú Lateral con Cierre de Sesión -->
            <div class="admin-sidebar-footer">
                @auth
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-sidebar-logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
                @endauth
                <div class="admin-sidebar-legal">
                    Consejo Comunitario &bull; Ley 70 de 1993
                </div>
            </div>
        </aside>

        <!-- ÁREA PRINCIPAL DE CONTENIDO (A LA DERECHA DEL MENÚ) -->
        <div class="admin-main">
            <!-- Barra Superior del Contenido -->
            <header class="admin-content-topbar">
                <div class="d-flex align-items-center justify-content-between w-100 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary d-lg-none" id="adminSidebarToggle2" aria-label="Abrir menú">
                            <i class="bi bi-list fs-5"></i>
                        </button>
                        <div class="admin-breadcrumbs">
                            <span class="text-muted small">Panel de Gobierno</span>
                            <span class="text-muted small">/</span>
                            <span class="fw-bold text-dark small">@yield('title', 'Inicio')</span>
                        </div>
                    </div>

                    @auth
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-role {{ Auth::user()->role->badgeClass() }} d-none d-sm-inline-flex" style="font-size: 0.74rem; padding: 4px 12px;">
                            <i class="bi {{ Auth::user()->role->icon() }}"></i> {{ Auth::user()->role->label() }}
                        </span>

                        <a href="{{ route('index') }}" class="btn btn-sm btn-admin-outline py-1 px-3 d-none d-md-inline-flex align-items-center gap-1" target="_blank" style="font-size: 0.78rem;">
                            <i class="bi bi-globe"></i> Portal Público
                        </a>
                    </div>
                    @endauth
                </div>
            </header>

            <!-- Cuerpo del Contenido con Notificaciones -->
            <main class="admin-content-body">
                <!-- Notificaciones de Sesión -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
                        <i class="bi bi-info-circle-fill fs-5 text-primary"></i>
                        <div>{{ session('info') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Pie de Página Institucional -->
            <footer class="admin-content-footer">
                <div class="relief-divider mb-3">
                    <div class="relief-divider-dot"></div>
                </div>
                <p class="mb-1 text-muted small fw-semibold">
                    Consejo Comunitario Zanjón de Garrapatero &bull; Autonomía, Identidad y Territorio Ancestral Afrocolombiano
                </p>
                <p class="text-secondary small mb-0" style="font-size: 0.78rem;">
                    Regido bajo la Ley 70 de 1993 y el Decreto 1745 de 1995. Sistema de Información y Censo Territorial.
                </p>
            </footer>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script de Control del Menú Lateral en Móviles -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('adminSidebarBackdrop');
            const toggle1 = document.getElementById('adminSidebarToggle');
            const toggle2 = document.getElementById('adminSidebarToggle2');

            const openSidebar = () => {
                sidebar?.classList.add('show');
                backdrop?.classList.add('show');
            };

            const closeSidebar = () => {
                sidebar?.classList.remove('show');
                backdrop?.classList.remove('show');
            };

            toggle1?.addEventListener('click', openSidebar);
            toggle2?.addEventListener('click', openSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            // Cerrar menú al hacer clic en un enlace en pantallas móviles
            document.querySelectorAll('.admin-nav-item').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        closeSidebar();
                    }
                });
            });
        });
    </script>
    @include('partials.sweetalert')
    @stack('scripts')
</body>
</html>
