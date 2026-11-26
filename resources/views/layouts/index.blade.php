<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2D51AD">
    <meta name="description" content="@yield('meta_description', 'Consejo Comunitario Zanjón de Garrapatero. Identidad, territorio, cultura y autonomía comunitaria afro.')">

    <title>@yield('title', config('app.name', 'Consejo Comunitario Zanjón de Garrapatero'))</title>

    <!-- Preconnect & Fonts -->
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Bootstrap 5.3 & Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Configuración Central de los 3 Colores Principales -->
    <link href="{{ asset('css/theme-colors.css') }}" rel="stylesheet">

    <!-- Custom Modern Glassmorphic Stylesheet -->
    <link href="{{ asset('css/index-layout.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body data-mode="luminous">
    <!-- Ambient Background Lighting -->
    <div class="ambient" aria-hidden="true"></div>

    <!-- Main Navigation Header (Fondo Blanco Institucional con Hover Efecto Agua) -->
    <nav class="navbar navbar-expand-lg navbar-white-glass" aria-label="Navegación principal">
        <div class="wrap w-100">
            <div class="d-flex align-items-center justify-content-between">
                <a class="navbar-brand d-flex align-items-center gap-3" href="{{ route('index') }}">
                    <img class="brand-mark" src="{{ asset('logo/logo.webp') }}" alt="Logo del Consejo Comunitario Zanjón de Garrapatero" width="44" height="44">
                    <span class="brand-name">
                        Zanjón de Garrapatero
                        <small>Consejo comunitario</small>
                    </span>
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal" aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menú de navegación" style="color: var(--brand-primary);">
                    <i class="bi bi-list fs-2"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse justify-content-end" id="menuPrincipal">
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('documentos*') ? 'active' : '' }}" href="{{ route('documentos') }}">
                            Documentos
                        </a>
                    </li>

                    <li class="nav-item ms-lg-2">
                        <a class="nav-link nav-cta {{ request()->routeIs('contacto') ? 'active' : '' }}" href="{{ route('contacto') }}">
                            Contáctanos <i class="bi bi-arrow-up-right ms-1"></i>
                        </a>
                    </li>

                    @if (Route::has('login'))
                        <li class="nav-item ms-lg-2">
                            <a class="nav-link" href="{{ Auth::check() ? route(Auth::user()->role->dashboardRoute()) : route('login') }}" title="Ingresar al sistema">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Ingresar
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main id="mainContent">
        @yield('content')
    </main>

    <!-- Modal Multimedia (Galería y Videos) -->
    <div class="modal fade modal-media" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-6 text-white" id="mediaModalLabel">Galería del Consejo</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="mediaImage" alt="" class="img-fluid mx-auto" hidden>
                    <video id="mediaVideo" controls playsinline class="w-100" hidden></video>
                </div>
            </div>
        </div>
    </div>

    <!-- Separador con Efecto de Relieve y Dota / Gota Central -->
    <div class="relief-divider" aria-hidden="true"></div>

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
                <span>Plataforma institucional comunitaria · Cauca, Colombia</span>
            </div>
        </div>
    </footer>

    <!-- Floating Social Media Bar (Centro-Derecha de la Pantalla con Colores Idénticos) -->
    <aside class="floating-social-dock" aria-label="Redes sociales comunitarias flotantes">
        <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-fb" title="Facebook Oficial" aria-label="Facebook">
            <i class="bi bi-facebook"></i>
        </a>
        <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-ig" title="Instagram Oficial" aria-label="Instagram">
            <i class="bi bi-instagram"></i>
        </a>
        <a href="https://wa.me/573150000000" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-wa" title="WhatsApp Comunitario" aria-label="WhatsApp">
            <i class="bi bi-whatsapp"></i>
        </a>
        <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-yt" title="YouTube Oficial" aria-label="YouTube">
            <i class="bi bi-youtube"></i>
        </a>
        <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-x" title="X (Twitter) Oficial" aria-label="X (Twitter)">
            <i class="bi bi-twitter-x"></i>
        </a>
        <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="social-icon-nav social-tt" title="TikTok Oficial" aria-label="TikTok">
            <i class="bi bi-tiktok"></i>
        </a>
    </aside>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="{{ asset('js/index-layout.js') }}" defer></script>
    @include('partials.sweetalert')

    @stack('scripts')
</body>
</html>
