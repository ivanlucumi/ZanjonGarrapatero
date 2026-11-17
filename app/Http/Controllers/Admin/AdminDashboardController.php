<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\LoginLog;
use App\Models\MediaItem;
use App\Models\Publication;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the role-tailored dashboard.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        // 1. Super Admin View Data ("ve todo")
        if ($user->isSuperAdmin()) {
            $totalUsers = User::query()->count();
            $usersByRole = [
                'super_admin' => User::query()->where('role', UserRole::SuperAdmin)->count(),
                'admin' => User::query()->where('role', UserRole::Admin)->count(),
                'usuario' => User::query()->where('role', UserRole::Usuario)->count(),
            ];
            $totalSurveys = Survey::query()->count();
            $activeSurveysCount = Survey::query()->where('status', 'active')->count();
            $totalResponses = SurveyResponse::query()->count();
            $activeVeredasCount = User::query()->whereNotNull('vereda')->distinct('vereda')->count('vereda');

            $recentUsers = User::query()->latest()->take(5)->get();
            $recentSurveys = Survey::query()->with('creator')->withCount('responses')->latest()->take(5)->get();
            $recentResponses = SurveyResponse::query()->with(['survey', 'user'])->latest()->take(6)->get();

            // CMS & Auditoría Global
            $totalPublications = Publication::query()->count();
            $totalActivities = Activity::query()->count();
            $totalMedia = MediaItem::query()->count();
            $recentAuditLogs = AuditLog::query()->latest('id')->take(6)->get();
            $recentLogins = LoginLog::query()->latest('id')->take(6)->get();

            return view('admin.dashboard', [
                'user' => $user,
                'role' => $user->role,
                'totalUsers' => $totalUsers,
                'usersByRole' => $usersByRole,
                'totalSurveys' => $totalSurveys,
                'activeSurveysCount' => $activeSurveysCount,
                'totalResponses' => $totalResponses,
                'activeVeredasCount' => $activeVeredasCount,
                'recentUsers' => $recentUsers,
                'recentSurveys' => $recentSurveys,
                'recentResponses' => $recentResponses,
                'totalPublications' => $totalPublications,
                'totalActivities' => $totalActivities,
                'totalMedia' => $totalMedia,
                'recentAuditLogs' => $recentAuditLogs,
                'recentLogins' => $recentLogins,
            ]);
        }

        // 2. Admin View Data ("encuesta")
        if ($user->isAdmin()) {
            $totalSurveys = Survey::query()->count();
            $activeSurveys = Survey::query()
                ->withCount('responses')
                ->latest()
                ->get();
            $activeSurveysCount = $activeSurveys->where('status', 'active')->count();
            $totalResponses = SurveyResponse::query()->count();

            $totalPublications = Publication::query()->count();
            $totalActivities = Activity::query()->count();
            $totalMedia = MediaItem::query()->count();

            $recentResponses = SurveyResponse::query()
                ->with(['survey', 'user'])
                ->latest()
                ->take(8)
                ->get();

            return view('admin.dashboard', [
                'user' => $user,
                'role' => $user->role,
                'totalSurveys' => $totalSurveys,
                'activeSurveysCount' => $activeSurveysCount,
                'totalResponses' => $totalResponses,
                'totalPublications' => $totalPublications,
                'totalActivities' => $totalActivities,
                'totalMedia' => $totalMedia,
                'activeSurveys' => $activeSurveys,
                'recentResponses' => $recentResponses,
            ]);
        }

        // 3. Usuario View Data ("comunero")
        $allActiveSurveys = Survey::query()
            ->where('status', 'active')
            ->where('is_published', true)
            ->targetedFor($user->vereda)
            ->latest()
            ->get();

        $myResponses = SurveyResponse::query()
            ->where('user_id', $user->id)
            ->with('survey')
            ->latest()
            ->get();

        $answeredSurveyIds = $myResponses->pluck('survey_id')->all();

        $pendingSurveys = $allActiveSurveys->filter(function (Survey $survey) use ($answeredSurveyIds): bool {
            return ! in_array($survey->id, $answeredSurveyIds, true);
        });

        return view('admin.dashboard', [
            'user' => $user,
            'role' => $user->role,
            'pendingSurveys' => $pendingSurveys,
            'myResponses' => $myResponses,
            'pendingCount' => $pendingSurveys->count(),
            'answeredCount' => $myResponses->count(),
        ]);
    }
}
