{{-- Favicon & Icono Institucional en todas las vistas --}}
<link rel="icon" type="image/webp" href="{{ asset('logo/logo.webp') }}">
<link rel="shortcut icon" type="image/webp" href="{{ asset('logo/logo.webp') }}">
<link rel="apple-touch-icon" href="{{ asset('logo/logo.webp') }}">

{{-- SEO Técnico para Indexación Rápida en Buscadores --}}
<link rel="canonical" href="{{ url()->current() }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="googlebot" content="index, follow">
<meta name="author" content="Consejo Comunitario Zanjón de Garrapatero">
<meta name="keywords" content="Consejo Comunitario Zanjón de Garrapatero, comunidades negras Cauca, Ley 70 de 1993, territorio afrocolombiano, Santander de Quilichao, censo comunitario, Guardia Cimarrona, gobierno propio, El Palmar, Santa Lucía, La Toma, Mazamorrero">
<meta name="geo.region" content="CO-CAU">
<meta name="geo.placename" content="Santander de Quilichao, Cauca, Colombia">

{{-- Open Graph (WhatsApp, Facebook, LinkedIn, Telegram) --}}
<meta property="og:type" content="website">
<meta property="og:locale" content="es_CO">
<meta property="og:site_name" content="Consejo Comunitario Zanjón de Garrapatero">
<meta property="og:title" content="@yield('title', 'Consejo Comunitario Zanjón de Garrapatero — Identidad, Territorio y Comunidad')">
<meta property="og:description" content="@yield('meta_description', 'Portal institucional oficial del Consejo Comunitario Zanjón de Garrapatero: identidad afrocolombiana, territorio colectivo, censo, noticias y eventos comunitarios en el Cauca.')">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:image" content="{{ asset('logo/logo.webp') }}">
<meta property="og:image:secure_url" content="{{ asset('logo/logo.webp') }}">
<meta property="og:image:type" content="image/webp">
<meta property="og:image:width" content="512">
<meta property="og:image:height" content="512">
<meta property="og:image:alt" content="Escudo oficial del Consejo Comunitario Zanjón de Garrapatero">

{{-- Twitter / X Card --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'Consejo Comunitario Zanjón de Garrapatero')">
<meta name="twitter:description" content="@yield('meta_description', 'Portal oficial del Consejo Comunitario Zanjón de Garrapatero — territorio, memoria, censo y autonomía comunitaria afro.')">
<meta name="twitter:image" content="{{ asset('logo/logo.webp') }}">

{{-- Datos Estructurados Schema.org (JSON-LD) para Google --}}
@php
    $orgSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'GovernmentOrganization',
        'name' => 'Consejo Comunitario Zanjón de Garrapatero',
        'url' => url('/'),
        'logo' => asset('logo/logo.webp'),
        'image' => asset('logo/logo.webp'),
        'description' => 'Autoridad étnica y territorial afrocolombiana en el departamento del Cauca, Colombia, amparada en la Ley 70 de 1993.',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Santander de Quilichao',
            'addressRegion' => 'Cauca',
            'addressCountry' => 'CO',
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($orgSchema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
