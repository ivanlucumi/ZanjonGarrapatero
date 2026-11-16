<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display the home / landing page with hero carousel and section overviews (Design 1: Top Navbar).
     */
    public function index(): View
    {
        return view('layouts.index.index');
    }

    /**
     * Display the alternative landing page (Design 2: Left Sidebar Layout).
     */
    public function index2(): View
    {
        return view('layouts.index.index2');
    }

    /**
     * Display the 'Nosotros' (About Us) institutional page.
     */
    public function nosotros(): View
    {
        return view('pages.nosotros');
    }

    /**
     * Display the 'Territorio' (Territory & Map) page.
     */
    public function territorio(): View
    {
        return view('pages.territorio');
    }

    /**
     * Display the 'Actividades' (Community Activities & Programs) page.
     */
    public function actividades(): View
    {
        return view('pages.actividades');
    }

    /**
     * Display the 'Galería' (Visual & Memory Gallery) page.
     */
    public function galeria(): View
    {
        return view('pages.galeria');
    }

    /**
     * Display the 'Actualidad' (News, Census & Communiques) page.
     */
    public function actualidad(): View
    {
        return view('pages.actualidad');
    }

    /**
     * Display the 'Videos' (Audiovisual Library) page.
     */
    public function videos(): View
    {
        return view('pages.videos');
    }

    /**
     * Display the 'Contacto' (Contact & Census Interest) page.
     */
    public function contacto(): View
    {
        return view('pages.contacto');
    }

    /**
     * Process contact and census interest inquiries securely.
     */
    public function submitContact(ContactRequest $request): RedirectResponse
    {
        // Validated request data is safe from mass assignment and injection
        $validated = $request->validated();

        // In production, dispatch a Job or Notification (e.g. Mail::to(...))
        // For now, return with a localized success notification
        return back()->with('status_success', '¡Gracias por comunicarte con el Consejo Comunitario! Hemos recibido tu mensaje y nos pondremos en contacto pronto.');
    }
}
