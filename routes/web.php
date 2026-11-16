<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::get('/contacto', [PageController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [PageController::class, 'submitContact'])
    ->name('contacto.store')
    ->middleware('throttle:6,1');

// Authentication routes
Auth::routes();

// Authenticated dashboard
Route::get('/home', [HomeController::class, 'index'])->name('home');
