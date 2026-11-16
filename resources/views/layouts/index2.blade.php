<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#172D4D">
    <meta name="description" content="@yield('meta_description', 'Consejo Comunitario Zanjón de Garrapatero — Diseño 2 con menú lateral izquierdo.')">

    <title>@yield('title', 'Diseño 2 · Menú Lateral — Consejo Comunitario Zanjón de Garrapatero')</title>

    <!-- Preconnect & Fonts -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Bootstrap 5.3 & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Modern Glassmorphic Stylesheet -->
    <link href="{{ asset('css/index-layout.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body data-mode="luminous">
    <!-- Ambient Background Lighting -->
    <div class="ambient" aria-hidden="true"></div>

    <!-- Mobile Topbar (Visible on screens < 992px) -->
    <header class="mobile-topbar" aria-label="Navegación móvil">
        <a class="d-flex align-items-center gap-2 text-white" href="{{ route('index2') }}">
            <img src="{{ asset('logo/logo.webp') }}" alt="Logo CCZG" width="36" height="36" class="brand-mark">
            <span class="fw-bold fs-6">Zanjón de Garrapatero</span>
        </a>
        <button class="btn btn-outline-light border-0 p-2" type="button" id="sidebarToggle" aria-label="Abrir menú lateral">
            <i class="bi bi-list fs-2" style="color: var(--brand-accent);"></i>
        </button>
    </header>

    <!-- Backdrop for mobile drawer -->
    <div class="sidebar-backdrop" aria-hidden="true"></div>

    <div class="layout-sidebar">
        <!-- Sidebar Navigation (Menú Lateral Izquierdo) -->
        <aside class="sidebar-glass" aria-label="Menú lateral principal">
            <div>
                <!-- Brand Header -->
                <div class="sidebar-brand">
                    <a href="{{ route('index2') }}" class="d-flex align-items-center gap-3">
                        <img src="{{ asset('logo/logo.webp') }}" alt="Logo del Consejo Comunitario Zanjón de Garrapatero" width="48" height="48" class="brand-mark">
                        <div class="sidebar-brand-name">
                            Zanjón de Garrapatero
                            <small>Consejo Comunitario</small>
                        </div>
                    </a>
                </div>

                <div class="mb-3 px-1">
                    <span class="sidebar-badge">
                        <i class="bi bi-layout-sidebar-reverse"></i> Diseño 2 · Menú Lateral
                    </span>
                </div>

                <!-- Navigation Links -->
                <nav>
                    <ul class="sidebar-nav">
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('index2') ? 'active' : '' }}" href="{{ route('index2') }}">
                                <i class="bi bi-house-door"></i> Inicio
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('nosotros') ? 'active' : '' }}" href="{{ route('nosotros') }}">
                                <i class="bi bi-people"></i> Nosotros
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('territorio') ? 'active' : '' }}" href="{{ route('territorio') }}">
                                <i class="bi bi-geo-alt"></i> Territorio
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('actividades') ? 'active' : '' }}" href="{{ route('actividades') }}">
                                <i class="bi bi-calendar-check"></i> Actividades
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('galeria') ? 'active' : '' }}" href="{{ route('galeria') }}">
                                <i class="bi bi-images"></i> Galería
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('actualidad') ? 'active' : '' }}" href="{{ route('actualidad') }}">
                                <i class="bi bi-newspaper"></i> Actualidad
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link {{ request()->routeIs('videos') ? 'active' : '' }}" href="{{ route('videos') }}">
                                <i class="bi bi-camera-reels"></i> Video
                            </a>
                        </li>
                        <li>
                            <a class="sidebar-link sidebar-cta {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                                <i class="bi bi-chat-square-dots"></i> Contáctanos
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Sidebar Footer: Session, Switcher & Rights -->
            <div class="sidebar-footer">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost w-100 justify-content-center mb-2 py-2 small">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar al Portal
                    </a>
                @else
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 mb-2" style="background: rgba(255,255,255,0.06); border: 1px solid var(--line);">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            <i class="bi bi-person-circle fs-5" style="color: var(--brand-accent);"></i>
                            <span class="small text-truncate text-white fw-bold">{{ Auth::user()->name }}</span>
                        </div>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();" class="text-danger small" title="Cerrar Sesión">
                            <i class="bi bi-box-arrow-right fs-5"></i>
                        </a>
                        <form id="sidebar-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>
                @endguest

                <div class="text-center pt-2">
                    <small style="color: rgba(244, 241, 234, 0.6); font-size: 0.72rem;">
                        Consejo Comunitario &copy; {{ date('Y') }}
                    </small>
                </div>
            </div>
        </aside>

        <!-- Main Content Area with Left Margin for Sidebar -->
        <div class="main-sidebar-layout">
            <main id="mainContent">
                @yield('content')
            </main>

            <!-- Institutional Footer -->
            <footer class="footer">
                <div class="wrap">
                    <div class="row g-4 align-items-center mb-4">
                        <div class="col-md-5">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <img src="{{ asset('logo/logo.webp') }}" alt="Logo Zanjón de Garrapatero" width="38" height="38" class="brand-mark">
                                <div>
                                    <strong class="text-white d-block">Consejo Comunitario Zanjón de Garrapatero</strong>
                                    <small class="text-secondary">Identidad, memoria y autonomía ancestral afro</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <nav class="footer-nav justify-content-md-end" aria-label="Enlaces rápidos de pie de página">
                                <a href="{{ route('index2') }}">Inicio (D2)</a>
                                <a href="{{ route('index') }}">Inicio (D1)</a>
                                <a href="{{ route('nosotros') }}">Nosotros</a>
                                <a href="{{ route('territorio') }}">Territorio</a>
                                <a href="{{ route('actividades') }}">Actividades</a>
                                <a href="{{ route('galeria') }}">Galería</a>
                                <a href="{{ route('actualidad') }}">Actualidad</a>
                                <a href="{{ route('videos') }}">Video</a>
                                <a href="{{ route('contacto') }}">Contacto</a>
                            </nav>
                        </div>
                    </div>
                    <div class="border-top pt-3 d-flex justify-content-between gap-3 flex-wrap small" style="border-color: var(--line) !important;">
                        <span>© {{ date('Y') }} Consejo Comunitario Zanjón de Garrapatero. Todos los derechos reservados.</span>
                        <span>Diseño 2 con Menú Lateral Izquierdo &middot; Glassmorphism &middot; Cauca, Colombia</span>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Modal Multimedia (Galería y Videos) -->
    <div class="modal fade modal-media" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-6 text-white" id="mediaModalLabel">Proyección Multimedia del Consejo</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="mediaImage" alt="" class="img-fluid mx-auto" hidden>
                    <video id="mediaVideo" controls playsinline class="w-100" hidden></video>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating Project Presentation Switcher (Escoger entre Index e Index2) -->
    <aside class="project-switcher" role="region" aria-label="Selector de diseño del proyecto">
        <span class="switcher-tag">
            <i class="bi bi-layers-half"></i> Diseños:
        </span>
        <a href="{{ route('index') }}" class="btn-switch-design" title="Cambiar a Diseño 1: Barra Superior Tradicional">
            <i class="bi bi-layout-text-window-reverse"></i> D1: Superior
        </a>
        <a href="{{ route('index2') }}" class="btn-switch-design active" title="Diseño 2 Activo: Menú Lateral Izquierdo">
            <i class="bi bi-layout-sidebar-reverse"></i> D2: Lateral
        </a>
    </aside>

    <!-- Mode Switcher (Luz / Noche) -->
    <div class="mode-switch" role="group" aria-label="Cambiar estilo de diseño">
        <button type="button" data-mode-button="luminous" class="active" title="Tema Luminoso (Diseño claro y abierto)">Luz</button>
        <button type="button" data-mode-button="origin" title="Tema Noche (Diseño oscuro)">Noche</button>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/index-layout.js') }}" defer></script>

    @stack('scripts')
</body>
</html>