<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#172D4D">

    <title>{{ config('app.name', 'Consejo Comunitario Zanjón de Garrapatero') }}</title>

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

    <!-- Topline Info Bar -->
    <div class="topline">
        <div class="wrap d-flex justify-content-between flex-wrap gap-2 align-items-center">
            <span><span class="dot"></span>Identidad · Territorio · Autonomía Afro</span>
            <span>Consejo Comunitario · {{ date('Y') }}</span>
        </div>
    </div>

    <!-- Main Navigation Header -->
    <nav class="navbar navbar-expand-lg" aria-label="Navegación principal">
        <div class="wrap w-100">
            <div class="d-flex align-items-center justify-content-between">
                <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('index') }}">
                    <img class="brand-mark" src="{{ asset('logo/logo.webp') }}" alt="Logo del Consejo Comunitario Zanjón de Garrapatero" width="44" height="44">
                    <span class="brand-name">
                        Zanjón de Garrapatero
                        <small>Consejo comunitario</small>
                    </span>
                </a>
                <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#appNavigation" aria-controls="appNavigation" aria-expanded="false" aria-label="Abrir menú de navegación">
                    <i class="bi bi-list fs-2"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="appNavigation">
                <ul class="navbar-nav align-items-lg-center gap-lg-1 my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                            Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('nosotros') ? 'active' : '' }}" href="{{ route('nosotros') }}">
                            Nosotros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('territorio') ? 'active' : '' }}" href="{{ route('territorio') }}">
                            Territorio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('actividades') ? 'active' : '' }}" href="{{ route('actividades') }}">
                            Actividades
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('galeria') ? 'active' : '' }}" href="{{ route('galeria') }}">
                            Galería
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('actualidad') ? 'active' : '' }}" href="{{ route('actualidad') }}">
                            Actualidad
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('videos') ? 'active' : '' }}" href="{{ route('videos') }}">
                            Video
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link nav-cta {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                            Contáctanos <i class="bi bi-arrow-up-right ms-1"></i>
                        </a>
                    </li>

                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item ms-lg-2">
                                <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>{{ __('Ingresar') }}
                                </a>
                            </li>
                        @endif

                        @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">
                                    <i class="bi bi-person-plus me-1"></i>{{ __('Registro') }}
                                </a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown ms-lg-2">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="bi bi-person-circle" style="color: var(--brand-accent);"></i> {{ Auth::user()->name }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="bi bi-speedometer2 me-2" style="color: var(--brand-accent);"></i>Panel de Usuario
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right me-2 text-danger"></i>{{ __('Cerrar Sesión') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="py-5" style="min-height: calc(100vh - 240px);">
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
                        <a href="{{ route('index') }}">Inicio</a>
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
                <span>Plataforma comunitaria con diseño Glassmorphism · Cauca, Colombia</span>
            </div>
        </div>
    </footer>

    <!-- Floating Project Presentation Switcher (Escoger entre Index e Index2) -->
    <aside class="project-switcher" role="region" aria-label="Selector de diseño del proyecto">
        <span class="switcher-tag">
            <i class="bi bi-layers-half"></i> Diseños:
        </span>
        <a href="{{ route('index') }}" class="btn-switch-design {{ request()->routeIs('index') ? 'active' : '' }}" title="Ver Diseño 1: Barra Superior Tradicional">
            <i class="bi bi-layout-text-window-reverse"></i> D1: Superior
        </a>
        <a href="{{ route('index2') }}" class="btn-switch-design {{ request()->routeIs('index2') ? 'active' : '' }}" title="Ver Diseño 2: Menú Lateral Izquierdo">
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
