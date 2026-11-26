<?php

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AuditController;
use App\Http\Controllers\Admin\BoardMemberController;
use App\Http\Controllers\Admin\CarouselSlideController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\MediaItemController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PublicationController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\TerritoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// RUTA DE LIMPIEZA
Route::get('limpiar', function () {
    // $php = Artisan::call('up');
    $php = Artisan::call('optimize:clear');
    $php6 = Artisan::call('config:cache');
    $php1 = Artisan::call('view:clear');
    $php2 = Artisan::call('cache:clear');
    $php3 = Artisan::call('route:clear');
    $php4 = Artisan::call('optimize:clear');

    return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| Web Routes - Consejo Comunitario Zanjón de Garrapatero
|--------------------------------------------------------------------------
*/

// Independent pages (Multi-page architecture)
Route::get('/', [PageController::class, 'index'])->name('index');
Route::get('/index2', [PageController::class, 'index2'])->name('index2');
Route::get('/nosotros', [PageController::class, 'nosotros'])->name('nosotros');
Route::get('/territorio', [PageController::class, 'territorio'])->name('territorio');
Route::get('/actividades', [PageController::class, 'actividades'])->name('actividades');
Route::get('/galeria', [PageController::class, 'galeria'])->name('galeria');
Route::get('/actualidad', [PageController::class, 'actualidad'])->name('actualidad');
Route::get('/videos', [PageController::class, 'videos'])->name('videos');
Route::get('/documentos', [PageController::class, 'documentos'])->name('documentos');
Route::get('/documentos/{slug}/descargar', [PageController::class, 'downloadDocument'])->name('documentos.download');
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');
Route::post('/contacto', [PageController::class, 'submitContact'])
    ->name('contacto.store')
    ->middleware('throttle:6,1');

// Authentication routes
Auth::routes();

// Redirect legacy home to admin dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Authenticated Admin & Community Portal routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function (): void {
    // Role-tailored dashboard and demo switcher
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/demo-switch-role', [UserController::class, 'switchDemoRole'])->name('demo.switch-role');

    // Surveys module (available according to role)
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/respond', [SurveyController::class, 'showResponseForm'])->name('surveys.respond');
    Route::post('/surveys/{survey}/respond', [SurveyController::class, 'submitResponse'])->name('surveys.submit');

    // CMS & Participation administration (Super Admin & Admin Encuestas/Contenidos)
    Route::middleware(['role:super_admin,admin'])->group(function (): void {
        Route::get('/surveys-manage/create', [SurveyController::class, 'create'])->name('surveys.create');
        Route::post('/surveys-manage', [SurveyController::class, 'store'])->name('surveys.store');
        Route::get('/surveys/{survey}/responses', [SurveyController::class, 'responses'])->name('surveys.responses');
        Route::patch('/surveys/{survey}/toggle-status', [SurveyController::class, 'toggleStatus'])->name('surveys.toggle-status');

        // Publications / Actualidad y Noticias CRUD
        Route::get('/publications', [PublicationController::class, 'index'])->name('publications.index');
        Route::get('/publications/create', [PublicationController::class, 'create'])->name('publications.create');
        Route::post('/publications', [PublicationController::class, 'store'])->name('publications.store');
        Route::get('/publications/{id}/edit', [PublicationController::class, 'edit'])->name('publications.edit');
        Route::put('/publications/{id}', [PublicationController::class, 'update'])->name('publications.update');
        Route::delete('/publications/{publication}', [PublicationController::class, 'destroy'])->name('publications.destroy');
        Route::patch('/publications/{id}/restore', [PublicationController::class, 'restore'])->name('publications.restore');
        Route::patch('/publications/{id}/toggle-visibility', [PublicationController::class, 'toggleVisibility'])->name('publications.toggle-visibility');

        // Activities / Agenda Comunitaria CRUD
        Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
        Route::get('/activities/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/activities', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/activities/{id}/edit', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::put('/activities/{id}', [ActivityController::class, 'update'])->name('activities.update');
        Route::delete('/activities/{activity}', [ActivityController::class, 'destroy'])->name('activities.destroy');
        Route::patch('/activities/{id}/restore', [ActivityController::class, 'restore'])->name('activities.restore');
        Route::patch('/activities/{id}/toggle-visibility', [ActivityController::class, 'toggleVisibility'])->name('activities.toggle-visibility');

        // Media items / Galería & Videos CRUD
        Route::get('/media', [MediaItemController::class, 'index'])->name('media.index');
        Route::get('/media/create', [MediaItemController::class, 'create'])->name('media.create');
        Route::post('/media', [MediaItemController::class, 'store'])->name('media.store');
        Route::get('/media/{id}/edit', [MediaItemController::class, 'edit'])->name('media.edit');
        Route::put('/media/{id}', [MediaItemController::class, 'update'])->name('media.update');
        Route::delete('/media/{mediaItem}', [MediaItemController::class, 'destroy'])->name('media.destroy');
        Route::patch('/media/{id}/restore', [MediaItemController::class, 'restore'])->name('media.restore');
        Route::patch('/media/{id}/toggle-visibility', [MediaItemController::class, 'toggleVisibility'])->name('media.toggle-visibility');

        // Carousel / Hero Slides CRUD
        Route::get('/carousel', [CarouselSlideController::class, 'index'])->name('carousel.index');
        Route::get('/carousel/create', [CarouselSlideController::class, 'create'])->name('carousel.create');
        Route::post('/carousel', [CarouselSlideController::class, 'store'])->name('carousel.store');
        Route::get('/carousel/{id}/edit', [CarouselSlideController::class, 'edit'])->name('carousel.edit');
        Route::put('/carousel/{id}', [CarouselSlideController::class, 'update'])->name('carousel.update');
        Route::delete('/carousel/{slide}', [CarouselSlideController::class, 'destroy'])->name('carousel.destroy');
        Route::patch('/carousel/{id}/restore', [CarouselSlideController::class, 'restore'])->name('carousel.restore');
        Route::patch('/carousel/{id}/toggle-visibility', [CarouselSlideController::class, 'toggleVisibility'])->name('carousel.toggle-visibility');

        // Contact Messages / Buzón de Atención y PQRS CRUD
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{id}', [ContactMessageController::class, 'show'])->name('messages.show');
        Route::put('/messages/{id}', [ContactMessageController::class, 'update'])->name('messages.update');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('messages.destroy');
        Route::patch('/messages/{id}/restore', [ContactMessageController::class, 'restore'])->name('messages.restore');

        // Territories / Veredas CRUD
        Route::get('/territories', [TerritoryController::class, 'index'])->name('territories.index');
        Route::get('/territories/create', [TerritoryController::class, 'create'])->name('territories.create');
        Route::post('/territories', [TerritoryController::class, 'store'])->name('territories.store');
        Route::get('/territories/{id}/edit', [TerritoryController::class, 'edit'])->name('territories.edit');
        Route::put('/territories/{id}', [TerritoryController::class, 'update'])->name('territories.update');
        Route::delete('/territories/{territory}', [TerritoryController::class, 'destroy'])->name('territories.destroy');
        Route::patch('/territories/{id}/restore', [TerritoryController::class, 'restore'])->name('territories.restore');
        Route::patch('/territories/{id}/toggle-visibility', [TerritoryController::class, 'toggleVisibility'])->name('territories.toggle-visibility');

        // Board Members / Autoridades y Gobierno Propio CRUD
        Route::get('/board', [BoardMemberController::class, 'index'])->name('board.index');
        Route::get('/board/create', [BoardMemberController::class, 'create'])->name('board.create');
        Route::post('/board', [BoardMemberController::class, 'store'])->name('board.store');
        Route::get('/board/{id}/edit', [BoardMemberController::class, 'edit'])->name('board.edit');
        Route::put('/board/{id}', [BoardMemberController::class, 'update'])->name('board.update');
        Route::delete('/board/{boardMember}', [BoardMemberController::class, 'destroy'])->name('board.destroy');
        Route::patch('/board/{id}/restore', [BoardMemberController::class, 'restore'])->name('board.restore');
        Route::patch('/board/{id}/toggle-visibility', [BoardMemberController::class, 'toggleVisibility'])->name('board.toggle-visibility');

        // Documents / Biblioteca y Repositorio Institucional CRUD
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{id}/edit', [DocumentController::class, 'edit'])->name('documents.edit');
        Route::put('/documents/{id}', [DocumentController::class, 'update'])->name('documents.update');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        Route::patch('/documents/{id}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
        Route::patch('/documents/{id}/toggle-visibility', [DocumentController::class, 'toggleVisibility'])->name('documents.toggle-visibility');
        Route::get('/documents/{id}/download', [DocumentController::class, 'download'])->name('documents.download');

        // Centro de Correos (Individual, Por Rol, Masivo)
        Route::get('/emails', [EmailController::class, 'index'])->name('emails.index');
        Route::get('/emails/create', [EmailController::class, 'create'])->name('emails.create');
        Route::post('/emails', [EmailController::class, 'store'])->name('emails.store');
        Route::get('/emails/{id}', [EmailController::class, 'show'])->name('emails.show');

        // Notificaciones en pantalla para administradores (Solicitudes y Correos entrantes)
        Route::get('/notifications/unread', [NotificationController::class, 'unread'])->name('notifications.unread');
    });

    // Super Admin exclusive module ("ve todo": Usuarios, Auditoría y Trazabilidad)
    Route::middleware(['role:super_admin'])->group(function (): void {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');

        // Auditoría completa
        Route::get('/audit', [AuditController::class, 'index'])->name('audit.index');
        Route::get('/audit/logins', [AuditController::class, 'logins'])->name('audit.logins');
    });
});
