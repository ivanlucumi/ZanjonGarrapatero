<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Activity;
use App\Models\BoardMember;
use App\Models\CarouselSlide;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\MediaItem;
use App\Models\Publication;
use App\Models\Territory;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PageController extends Controller
{
    /**
     * Display the home / landing page with hero carousel and section overviews (Design 1: Top Navbar).
     */
    public function index(): View
    {
        try {
            $carouselSlides = CarouselSlide::query()->visible()->ordered()->get();
            $publications = Publication::query()->visible()->latest('published_at')->take(3)->get();
            $activities = Activity::query()->visible()->orderBy('event_date')->take(3)->get();
            $featuredMedia = MediaItem::query()->visible()->where('is_featured', true)->take(6)->get();
            $territories = Territory::query()->visible()->ordered()->take(4)->get();
        } catch (Throwable) {
            $carouselSlides = new Collection;
            $publications = new Collection;
            $activities = new Collection;
            $featuredMedia = new Collection;
            $territories = new Collection;
        }

        return view('layouts.index.index', [
            'carouselSlides' => $carouselSlides,
            'publications' => $publications,
            'activities' => $activities,
            'featuredMedia' => $featuredMedia,
            'territories' => $territories,
        ]);
    }

    /**
     * Display the alternative landing page (Design 2: Left Sidebar Layout).
     */
    public function index2(): View
    {
        try {
            $carouselSlides = CarouselSlide::query()->visible()->ordered()->get();
            $publications = Publication::query()->visible()->latest('published_at')->take(3)->get();
            $activities = Activity::query()->visible()->orderBy('event_date')->take(3)->get();
            $featuredMedia = MediaItem::query()->visible()->where('is_featured', true)->take(6)->get();
            $territories = Territory::query()->visible()->ordered()->take(4)->get();
        } catch (Throwable) {
            $carouselSlides = new Collection;
            $publications = new Collection;
            $activities = new Collection;
            $featuredMedia = new Collection;
            $territories = new Collection;
        }

        return view('layouts.index.index2', [
            'carouselSlides' => $carouselSlides,
            'publications' => $publications,
            'activities' => $activities,
            'featuredMedia' => $featuredMedia,
            'territories' => $territories,
        ]);
    }

    /**
     * Display the 'Nosotros' (About Us) institutional page.
     */
    public function nosotros(): View
    {
        try {
            $boardMembers = BoardMember::query()->visible()->ordered()->get();
        } catch (Throwable) {
            $boardMembers = new Collection;
        }

        return view('pages.nosotros', [
            'boardMembers' => $boardMembers,
        ]);
    }

    /**
     * Display the 'Territorio' (Territory & Map) page.
     */
    public function territorio(): View
    {
        try {
            $territories = Territory::query()->visible()->ordered()->get();
        } catch (Throwable) {
            $territories = new Collection;
        }

        return view('pages.territorio', [
            'territories' => $territories,
        ]);
    }

    /**
     * Display the 'Actividades' (Community Activities & Programs) page.
     */
    public function actividades(): View
    {
        try {
            $activities = Activity::query()->visible()->orderBy('event_date')->get();
        } catch (Throwable) {
            $activities = new Collection;
        }

        return view('pages.actividades', [
            'activities' => $activities,
        ]);
    }

    /**
     * Display the 'Galería' (Visual & Memory Gallery) page.
     */
    public function galeria(): View
    {
        try {
            $images = MediaItem::query()->visible()->where('type', 'image')->latest('id')->get();
        } catch (Throwable) {
            $images = new Collection;
        }

        return view('pages.galeria', [
            'images' => $images,
        ]);
    }

    /**
     * Display the 'Actualidad' (News, Census & Communiques) page.
     */
    public function actualidad(): View
    {
        try {
            $publications = Publication::query()->visible()->latest('published_at')->paginate(9);
        } catch (Throwable) {
            $publications = Publication::query()->paginate(9);
        }

        return view('pages.actualidad', [
            'publications' => $publications,
        ]);
    }

    /**
     * Display the 'Videos' (Audiovisual Library) page.
     */
    public function videos(): View
    {
        try {
            $videos = MediaItem::query()->visible()->where('type', 'video')->latest('id')->get();
        } catch (Throwable) {
            $videos = new Collection;
        }

        return view('pages.videos', [
            'videos' => $videos,
        ]);
    }

    /**
     * Display the 'Contacto' (Contact & Census Interest) page.
     */
    public function contacto(): View
    {
        return view('pages.contacto');
    }

    /**
     * Handle the contact / census inquiry form submission.
     */
    public function submitContact(ContactRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'territory' => $validated['territory'],
            'message' => $validated['message'],
            'status' => 'pendiente',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        AuditService::log(
            action: 'radicar_contacto_web',
            entity: $contactMessage,
            description: "Nuevo mensaje de contacto o trámite de censo de '{$contactMessage->name}' ({$contactMessage->territory}).",
            properties: [
                'email' => $contactMessage->email,
                'territory' => $contactMessage->territory,
            ]
        );

        $message = '¡Gracias por comunicarte con el Consejo Comunitario Zanjón de Garrapatero! Tu mensaje ha sido radicado exitosamente. La junta directiva o el enlace veredal se pondrá en contacto pronto.';

        return redirect()->route('contacto')
            ->with('success', $message)
            ->with('status_success', $message);
    }

    /**
     * Display the public 'Documentos' institutional library and repository.
     */
    public function documentos(Request $request): View
    {
        try {
            $query = Document::query()->visible();

            if ($request->filled('search')) {
                $search = $request->string('search')->toString();
                $query->where(function ($q) use ($search): void {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('file_name', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category')) {
                $query->where('category', $request->string('category')->toString());
            }

            $documents = $query->latest()->paginate(9)->withQueryString();
        } catch (Throwable) {
            $documents = new Collection;
        }

        $categories = [
            'Normatividad y Ley 70',
            'Actas de Asamblea',
            'Planes de Manejo Ambiental',
            'Formatos y Trámites',
            'Gacetas y Resoluciones',
            'Censo y Cartografía',
        ];

        return view('pages.documentos', [
            'documents' => $documents,
            'categories' => $categories,
            'currentSearch' => $request->string('search')->toString(),
            'currentCategory' => $request->string('category')->toString(),
        ]);
    }

    /**
     * Download an institutional document from public portal.
     */
    public function downloadDocument(string $slug): StreamedResponse|RedirectResponse
    {
        $document = Document::query()->visible()->where('slug', $slug)->firstOrFail();

        if (! Storage::disk('public')->exists($document->file_path)) {
            return back()->with('error', 'El documento solicitado no se encuentra disponible temporalmente.');
        }

        $document->increment('download_count');

        AuditService::log(
            action: 'descarga_documento_web',
            entity: $document,
            description: "Descarga comunitaria del documento '{$document->title}' ({$document->formattedSize()}).",
            properties: ['download_count' => $document->download_count]
        );

        return Storage::disk('public')->download($document->file_path, $document->file_name);
    }
}
