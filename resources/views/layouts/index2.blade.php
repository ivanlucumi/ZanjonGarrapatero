<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#172D4D">
    <meta name="description" content="@yield('meta_description', 'Consejo Comunitario Zanjón de Garrapatero. Diseño con menú lateral izquierdo.')">

    <title>@yield('title', 'Diseño 2 · Menú lateral · Consejo Comunitario Zanjón de Garrapatero')</title>

    @include('partials.seo-meta')

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

                {{-- Separador con Efecto Relieve y Dota en Menú Lateral --}}
                <div class="relief-divider relief-compact" aria-hidden="true"></div>

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
                            <a class="sidebar-link {{ request()->routeIs('documentos*') ? 'active' : '' }}" href="{{ route('documentos') }}">
                                <i class="bi bi-file-earmark-text"></i> Documentos
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

            {{-- Separador con Efecto Relieve y Dota en Menú Lateral --}}
            <div class="relief-divider relief-compact" aria-hidden="true"></div>

            <!-- Sidebar Footer: Ingresar & Rights -->
            <div class="sidebar-footer">
                <a href="{{ Auth::check() ? route(Auth::user()->role->dashboardRoute()) : route('login') }}" class="btn btn-ghost w-100 justify-content-center mb-2 py-2 small">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Ingresar
                </a>

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
                        <span>Plataforma institucional comunitaria · Menú lateral · Cauca, Colombia</span>
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