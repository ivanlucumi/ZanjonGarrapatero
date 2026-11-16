<?php

namespace Tests\Feature;

use Tests\TestCase;

class IndexLayoutTest extends TestCase
{
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
     * Test that the official color triad (#172D4D, #F4F1EA, #B58A45) is integrated.
     */
    public function test_official_color_triad_is_configured_and_displayed(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('#172D4D');
        $response->assertSee('#F4F1EA');
        $response->assertSee('#B58A45');

        $css = file_get_contents(public_path('css/index-layout.css'));
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
     * Test that the carousel supports both image and video projection with glassmorphic panels.
     */
    public function test_carousel_projects_images_and_videos_with_glassmorphism(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Verification of carousel structure & media projection
        $response->assertSee('id="inicioCarousel"', false);
        $response->assertSee('carousel-media');
        $response->assertSee('carousel-glass-panel');
        $response->assertSee('btn-projection');
        $response->assertSee('data-media-type="image"', false);
        $response->assertSee('data-media-type="video"', false);
        $response->assertSee('carousel-control-glass');
        $response->assertSee('carousel-indicators-glass');
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
}
