<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSurveyRequest;
use App\Http\Requests\Admin\SubmitSurveyResponseRequest;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    /**
     * Display a listing of surveys.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        if ($user->isUsuario()) {
            $surveys = Survey::query()
                ->where('is_published', true)
                ->targetedFor($user->vereda)
                ->withCount('responses')
                ->latest()
                ->paginate(10);

            $myResponseSurveyIds = SurveyResponse::query()
                ->where('user_id', $user->id)
                ->pluck('survey_id')
                ->all();

            return view('admin.surveys.index', [
                'user' => $user,
                'surveys' => $surveys,
                'myResponseSurveyIds' => $myResponseSurveyIds,
            ]);
        }

        // SuperAdmin & Admin
        $surveys = Survey::query()
            ->with('creator')
            ->withCount('responses')
            ->latest()
            ->paginate(15);

        return view('admin.surveys.index', [
            'user' => $user,
            'surveys' => $surveys,
            'myResponseSurveyIds' => [],
        ]);
    }

    /**
     * Show form for creating a new survey (SuperAdmin & Admin).
     */
    public function create(): View
    {
        return view('admin.surveys.create');
    }

    /**
     * Store a newly created survey.
     */
    public function store(StoreSurveyRequest $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validated();

        $survey = new Survey;
        $survey->title = $validated['title'];
        $survey->slug = Str::slug($validated['title']).'-'.Str::random(6);
        $survey->category = $validated['category'];
        $survey->description = $validated['description'] ?? null;
        $survey->target_vereda = $validated['target_vereda'] ?? null;
        $survey->status = $validated['status'];
        $survey->is_published = true;
        $survey->questions = $validated['questions'];
        $survey->created_by = $user->id;
        $survey->save();

        return redirect()->route('admin.surveys.index')->with('success', '¡Encuesta comunitaria creada exitosamente!');
    }

    /**
     * Display survey structure and details.
     */
    public function show(Survey $survey): View
    {
        $survey->loadCount('responses');

        return view('admin.surveys.show', [
            'survey' => $survey,
        ]);
    }

    /**
     * Show the answering form for a survey (for comuneros/users).
     */
    public function showResponseForm(Request $request, Survey $survey): View|RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $existingResponse = SurveyResponse::query()
            ->where('survey_id', $survey->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingResponse) {
            return redirect()->route('admin.surveys.index')
                ->with('info', 'Usted ya ha diligenciado y registrado esta encuesta con anterioridad.');
        }

        if (! $survey->isActive()) {
            return redirect()->route('admin.surveys.index')
                ->with('error', 'Esta encuesta se encuentra cerrada o en borrador.');
        }

        return view('admin.surveys.respond', [
            'survey' => $survey,
            'user' => $user,
        ]);
    }

    /**
     * Store response submitted by a user.
     */
    public function submitResponse(SubmitSurveyResponseRequest $request, Survey $survey): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($survey->id !== (int) $request->validated('survey_id')) {
            abort(400, 'Identificador de encuesta inconsistente.');
        }

        if (! $survey->isActive()) {
            return redirect()->route('admin.surveys.index')->with('error', 'Esta encuesta está cerrada.');
        }

        // Avoid duplicate response
        $existing = SurveyResponse::query()
            ->where('survey_id', $survey->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('admin.surveys.index')
                ->with('info', 'Ya había registrado su respuesta para esta encuesta.');
        }

        SurveyResponse::query()->create([
            'survey_id' => $survey->id,
            'user_id' => $user->id,
            'answers' => $request->validated('answers'),
            'notes' => $request->validated('notes'),
            'status' => 'submitted',
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', '¡Agradecemos su participación! Su respuesta ha sido registrada en el Censo Comunitario.');
    }

    /**
     * List responses for a specific survey (SuperAdmin & Admin).
     */
    public function responses(Survey $survey): View
    {
        $responses = $survey->responses()
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('admin.surveys.responses', [
            'survey' => $survey,
            'responses' => $responses,
        ]);
    }

    /**
     * Toggle survey status between active and closed (SuperAdmin & Admin).
     */
    public function toggleStatus(Survey $survey): RedirectResponse
    {
        $newStatus = $survey->status === 'active' ? 'closed' : 'active';
        $survey->update(['status' => $newStatus]);

        $message = $newStatus === 'active' ? 'Encuesta reabierta y activa.' : 'Encuesta cerrada correctamente.';

        return back()->with('success', $message);
    }
}
