<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Redirect authenticated users to their role-specific destination.
     */
    public function index(Request $request): RedirectResponse
    {
        $routeName = $request->user()?->role?->dashboardRoute() ?? 'admin.dashboard';

        return redirect()->route($routeName);
    }
}
