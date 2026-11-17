<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexLayoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the index page loads successfully with the integrated layout and logo.
     */
    public function test_index_page_loads_with_layout_and_logo(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Zanjón de Garrapatero');
        $response->assertSee('Consejo comunitario');
        $response->assertSee(asset('logo/logo.webp'));
        $response->assertSee(asset('css/index-layout.css'));
        $response->assertSee(asset('js/index-layout.js'));
        $response->assertSee(route('nosotros'));
        $response->assertSee(route('territorio'));
        $response->assertSee(route('actividades'));
        $response->assertSee(route('galeria'));
        $response->assertSee(route('actualidad'));
        $response->assertSee(route('videos'));
        $response->assertSee(route('contacto'));
    }

    /**
     * Test that each independent page loads with HTTP 200 and relevant content.
     */
    public function test_each_independent_page_loads_with_correct_view(): void
    {
        // 1. Nosotros
        $nosotros = $this->get(route('nosotros'));
        $nosotros->assertStatus(200);
        $nosotros->assertSee('Gobierno propio, memoria y futuro colectivo');
        $nosotros->assertSee('Ley 70');

        // 2. Territorio
        $territorio = $this->get(route('territorio'));
        $territorio->assertStatus(200);
        $territorio->assertSee('Un mapa de relaciones, cuencas y arraigo');
        $territorio->assertSee('El Palmar');
        $territorio->assertSee('Santa Lucía');
        $territorio->assertSee('La Toma');
        $territorio->assertSee('Mazamorrero');

        // 3. Actividades
        $actividades = $this->get(route('actividades'));
        $actividades->assertStatus(200);
        $actividades->assertSee('La comunidad se fortalece en el encuentro');
        $actividades->assertSee('Asambleas Comunitarias');
        $actividades->assertSee('Guardia Cimarrona');

        // 4. Galería
        $galeria = $this->get(route('galeria'));
        $galeria->assertStatus(200);
        $galeria->assertSee('Rostros, paisajes y memoria viva');
        $galeria->assertSee('data-mode="luminous"', false);

        // 5. Actualidad
        $actualidad = $this->get(route('actualidad'));
        $actualidad->assertStatus(200);
        $actualidad->assertSee('Noticias, acuerdos y vida en el territorio');
        $actualidad->assertSee('Censo Territorial');

        // 6. Videos
        $videos = $this->get(route('videos'));
        $videos->assertStatus(200);
        $videos->assertSee('La voz del territorio en movimiento');
        $videos->assertSee('video-card');

        // 7. Contacto
        $contacto = $this->get(route('contacto'));
        $contacto->assertStatus(200);
        $contacto->assertSee('Hablemos del territorio');
        $contacto->assertSee('comunicaciones@consejozanjondegarrapatero.org');
        $contacto->assertSee('name="territory"', false);
    }

    /**
     * Test that the official color triad (#172D4D, #F4F1EA, #B58A45) is configured in theme-colors.css and displayed.
     */
    public function test_official_color_triad_is_configured_and_displayed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('#172D4D');
        $response->assertSee('#F4F1EA');
        $response->assertSee('#B58A45');
        $response->assertSee('theme-colors.css');

        $this->assertFileExists(public_path('css/theme-colors.css'));
        $themeColors = file_get_contents(public_path('css/theme-colors.css'));
        $this->assertStringContainsString('--brand-primary:', $themeColors);
        $this->assertStringContainsString('--brand-secondary:', $themeColors);
        $this->assertStringContainsString('--brand-accent:', $themeColors);

        $css = file_get_contents(public_path('css/index-layout.css'));
        $this->assertStringContainsString('theme-colors.css', $css);
        $this->assertStringContainsString('#172d4d', $css);
        $this->assertStringContainsString('#f4f1ea', $css);
        $this->assertStringContainsString('#b58a45', $css);
    }

    /**
     * Test contact form validation and successful submission.
     */
    public function test_contact_form_submits_successfully_and_validates(): void
    {
        // Test required fields validation
        $invalid = $this->post(route('contacto.store'), []);
        $invalid->assertSessionHasErrors(['name', 'email', 'territory', 'message']);

        // Test valid submission
        $valid = $this->post(route('contacto.store'), [
            'name' => 'María Lucumí Mina',
            'email' => 'maria.lucumi@ejemplo.com',
            'phone' => '3157894561',
            'territory' => 'El Palmar',
            'message' => 'Deseo registrar a mi familia en el censo ampliado del Consejo Comunitario.',
        ]);

        $valid->assertRedirect();
        $valid->assertSessionHas('status_success');
    }

    /**
     * Test that the carousel supports both image and video projection with glassmorphic panels and smooth loop transitions.
     */
    public function test_carousel_projects_images_and_videos_with_glassmorphism(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verification of carousel structure & media projection
        $response->assertSee('id="inicioCarousel"', false);
        $response->assertSee('carousel-fade');
        $response->assertSee('data-bs-wrap="true"', false);
        $response->assertSee('carousel-media');
        $response->assertSee('carousel-glass-panel');
        $response->assertSee('has-media');
        $response->assertSee('slide-no-media');
        $response->assertSee('btn-projection');
        $response->assertSee('data-media-type="image"', false);
        $response->assertSee('data-media-type="video"', false);
        $response->assertSee('carousel-control-glass');
        $response->assertSee('carousel-indicators');
        $response->assertSee('carousel-indicators-glass');

        // Verification of smooth transition styles in CSS
        $css = file_get_contents(public_path('css/index-layout.css'));
        $this->assertStringContainsString('.hero-carousel .carousel.carousel-fade .carousel-item', $css);
        $this->assertStringContainsString('opacity 0.85s', $css);
        $this->assertStringNotContainsString('.hero-carousel .carousel-item {
  position: relative;
  min-height: clamp(500px, 62vh, 660px);
  padding: 0;
  display: flex;', $css);
    }

    /**
     * Test that auth pages (login, register) inherit glassmorphism and institutional brand.
     */
    public function test_auth_pages_render_with_glassmorphism_and_brand(): void
    {
        $login = $this->get(route('login'));
        $login->assertStatus(200);
        $login->assertSee('glass-card');
        $login->assertSee('form-glass-input');
        $login->assertSee('logo.webp');
        $login->assertSee('login-split');
        $login->assertSee(route('index2'));

        $register = $this->get(route('register'));
        $register->assertStatus(200);
        $register->assertSee('glass-card');
        $register->assertSee('form-glass-input');
        $register->assertSee('logo.webp');
    }

    /**
     * Test that index2 loads with the left sidebar layout and multimedia carousel.
     */
    public function test_index2_page_loads_with_left_sidebar_layout(): void
    {
        $response = $this->get(route('index2'));
        $response->assertStatus(200);
        $response->assertSee('sidebar-glass');
        $response->assertSee('sidebar-brand');
        $response->assertSee('Menú Lateral');
        $response->assertSee('id="inicioCarousel"', false);
        $response->assertSee('project-switcher');
    }

    /**
     * Test that the presentation switcher allows choosing between index and index2.
     */
    public function test_project_switcher_allows_toggling_between_index_and_index2(): void
    {
        // On Design 1 (Top Navbar)
        $d1 = $this->get(route('index'));
        $d1->assertStatus(200);
        $d1->assertSee(route('index2'));
        $d1->assertSee('project-switcher');
        $d1->assertSee('D1: Superior');
        $d1->assertSee('D2: Lateral');

        // On Design 2 (Left Sidebar)
        $d2 = $this->get(route('index2'));
        $d2->assertStatus(200);
        $d2->assertSee(route('index'));
        $d2->assertSee('project-switcher');
        $d2->assertSee('D1: Superior');
        $d2->assertSee('D2: Lateral');
    }

    /**
     * Test flat neutral background #ece7df, relief dividers and dota/gota effect in both versions and all views.
     */
    public function test_relief_dividers_and_flat_neutral_background_in_both_versions_and_all_views(): void
    {
        // 1. Verify CSS definition of flat neutral background #ece7df and relief with dota effect
        $themeColors = file_get_contents(public_path('css/theme-colors.css'));
        $this->assertStringContainsString('#ece7df', $themeColors);
        $this->assertStringContainsString('--relief-groove-shadow', $themeColors);

        $css = file_get_contents(public_path('css/index-layout.css'));
        $this->assertStringContainsString('#ece7df', $css);
        $this->assertStringContainsString('.relief-divider', $css);
        $this->assertStringContainsString('.relief-divider::before', $css);
        $this->assertStringContainsString('.relief-divider::after', $css);

        // 2. Verify Design 1 (Top horizontal navbar) has relief dividers
        $d1 = $this->get(route('index'));
        $d1->assertStatus(200);
        $d1->assertSee('relief-divider');

        // 3. Verify Design 2 (Left sidebar navbar) has relief dividers
        $d2 = $this->get(route('index2'));
        $d2->assertStatus(200);
        $d2->assertSee('relief-divider');
        $d2->assertSee('relief-compact');

        // 4. Verify all independent page views have relief dividers separating information
        $pages = ['nosotros', 'territorio', 'actividades', 'galeria', 'actualidad', 'videos', 'contacto'];
        foreach ($pages as $page) {
            $response = $this->get(route($page));
            $response->assertStatus(200);
            $response->assertSee('relief-divider');
        }

        // 5. Verify Auth layout has relief dividers
        $login = $this->get(route('login'));
        $login->assertStatus(200);
        $login->assertSee('relief-divider');
    }

    /**
     * Test that the top bar (Identidad · Territorio · Autonomía) is removed,
     * social media buttons are removed from the menu and kept exclusively on the floating dock (right side on desktop, bottom on mobile).
     */
    public function test_topline_is_removed_and_social_media_icons_with_official_colors_are_displayed(): void
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);

        // Verify topline with the text "Identidad · Territorio · Autonomía Afro" is removed
        $response->assertDontSee('class="topline"', false);
        $response->assertDontSee('Identidad · Territorio · Autonomía Afro');

        // Verify social media icons are removed from the navigation menu
        $response->assertDontSee('navbar-social-group');

        // Verify social media dock is present on the right/bottom with all official icons
        $response->assertSee('floating-social-dock');
        $response->assertSee('social-fb');
        $response->assertSee('social-ig');
        $response->assertSee('social-wa');
        $response->assertSee('social-yt');
        $response->assertSee('social-x');
        $response->assertSee('social-tt');

        // Verify CSS defines the identical official colors
        $css = file_get_contents(public_path('css/index-layout.css'));
        $this->assertStringContainsString('#1877F2', $css); // Facebook
        $this->assertStringContainsString('#25D366', $css); // WhatsApp
        $this->assertStringContainsString('#FF0000', $css); // YouTube
        $this->assertStringContainsString('#000000', $css); // X
        $this->assertStringContainsString('.floating-social-dock', $css);
    }

    /**
     * Test that social buttons dock at the bottom on mobile/small screens,
     * cards have scroll transitions, and multi-layered shadows with waterdrop effects are configured.
     */
    public function test_mobile_bottom_social_dock_scroll_transitions_and_waterdrop_shadows_are_configured(): void
    {
        $response = $this->get(route('index'));
        $response->assertStatus(200);

        $css = file_get_contents(public_path('css/index-layout.css'));
        $js = file_get_contents(public_path('js/index-layout.js'));

        // 1. Verify mobile dock positioning when disappearing from side on smaller screens
        $this->assertStringContainsString('@media (max-width: 991px)', $css);
        $this->assertStringContainsString('bottom: 16px', $css);
        $this->assertStringContainsString('translateX(-50%)', $css);
        $this->assertStringContainsString('border-radius: 999px', $css);

        // 2. Verify scroll transition rules for cards
        $this->assertStringContainsString('.scroll-card-reveal', $css);
        $this->assertStringContainsString('.scroll-card-in', $css);
        $this->assertStringContainsString('prefers-reduced-motion', $css);

        // 3. Verify JavaScript IntersectionObserver for scroll transitions
        $this->assertStringContainsString('IntersectionObserver', $js);
        $this->assertStringContainsString('scroll-card-reveal', $js);
        $this->assertStringContainsString('scroll-card-in', $js);
        $this->assertStringContainsString('.glass-card', $js);
        $this->assertStringContainsString('.territory', $js);
        $this->assertStringContainsString('.activity-card', $js);

        // 4. Verify multi-layer shadows and waterdrop ("efecto gotas") reflection styling
        $this->assertStringContainsString('.glass-card::before', $css);
        $this->assertStringContainsString('.glass-card::after', $css);
        $this->assertStringContainsString('.efecto-gota', $css);
        $this->assertStringContainsString('radial-gradient', $css);
        $this->assertStringContainsString('inset 0 1.5px 3px', $css);
    }
}
