<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminEmailRequest;
use App\Mail\InstitutionalBroadcastMail;
use App\Models\SentEmail;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class EmailController extends Controller
{
    /**
     * Límite máximo total para adjuntos directos en correo (10 MB).
     * Si se supera, se envían como enlaces de descarga directa.
     */
    public const MAX_DIRECT_ATTACHMENT_BYTES = 10 * 1024 * 1024;

    /**
     * Display the history of institutional emails sent (Individual, Role, Massive).
     */
    public function index(Request $request): View
    {
        $sendType = $request->query('send_type', 'all');

        $query = SentEmail::query()->with(['sender', 'recipientUser']);

        if (in_array($sendType, ['individual', 'role', 'massive'], true)) {
            $query->where('send_type', $sendType);
        }

        $emails = $query->latest('id')->paginate(12)->withQueryString();

        return view('admin.emails.index', [
            'emails' => $emails,
            'sendType' => $sendType,
        ]);
    }

    /**
     * Show the compose form for individual/multi-select, by-role, or massive emails.
     */
    public function create(): View
    {
        $users = User::query()->orderBy('name')->get(['id', 'name', 'email', 'role', 'vereda']);

        $roleCounts = [
            UserRole::SuperAdmin->value => User::query()->where('role', UserRole::SuperAdmin)->count(),
            UserRole::Admin->value => User::query()->where('role', UserRole::Admin)->count(),
            UserRole::Usuario->value => User::query()->where('role', UserRole::Usuario)->count(),
        ];

        return view('admin.emails.create', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'roleCounts' => $roleCounts,
            'totalUsersCount' => $users->count(),
        ]);
    }

    /**
     * Dispatch synchronous email(s) with automatic retries and smart attachment/link handling.
     */
    public function store(StoreAdminEmailRequest $request): RedirectResponse
    {
        /** @var User $sender */
        $sender = $request->user();
        $validated = $request->validated();

        $sendType = $validated['send_type'];

        if ($sendType === 'individual') {
            $ids = collect($validated['recipient_user_ids'] ?? [])
                ->when(! empty($validated['recipient_user_id']), fn ($c) => $c->push((int) $validated['recipient_user_id']))
                ->unique()
                ->filter()
                ->values();

            if ($ids->isEmpty()) {
                return back()
                    ->withInput()
                    ->withErrors(['recipient_user_ids' => 'Debes seleccionar al menos una persona inscrita.']);
            }

            $recipients = User::query()->whereIn('id', $ids)->get();
        } elseif ($sendType === 'role') {
            $recipients = User::query()->where('role', $validated['target_role'])->get();
        } else {
            $recipients = User::query()->get();
        }

        if ($recipients->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'No se encontraron usuarios inscritos válidos para el tipo de envío seleccionado.');
        }

        // Procesar archivos adjuntos (si superan el límite, se envían como enlaces de descarga)
        [$attachedFiles, $downloadLinks, $attachmentsMeta] = $this->prepareAttachments($request);

        $sentEmailsList = [];
        $failedRecipients = [];

        // Envío síncrono compatible con hosting compartido (sin colas) y reintentos automáticos
        foreach ($recipients as $recipient) {
            try {
                retry(3, function () use ($recipient, $validated, $sender, $attachedFiles, $downloadLinks): void {
                    Mail::to($recipient->email)->send(
                        new InstitutionalBroadcastMail(
                            emailSubject: $validated['subject'],
                            emailBody: $validated['body'],
                            recipientName: $recipient->name,
                            senderName: $sender->name,
                            attachedFiles: $attachedFiles,
                            downloadLinks: $downloadLinks,
                        )
                    );
                }, 200);

                $sentEmailsList[] = $recipient->email;
            } catch (Throwable $e) {
                $failedRecipients[] = [
                    'email' => $recipient->email,
                    'error' => $e->getMessage(),
                ];

                Log::error("Fallo definitivo al enviar correo a {$recipient->email} tras 3 intentos: {$e->getMessage()}", [
                    'recipient_id' => $recipient->id,
                    'subject' => $validated['subject'],
                ]);
            }
        }

        $sentEmail = SentEmail::create([
            'sender_id' => $sender->id,
            'send_type' => $sendType,
            'target_role' => $validated['target_role'] ?? null,
            'recipient_user_id' => $recipients->count() === 1 ? $recipients->first()->id : null,
            'subject' => $validated['subject'],
            'body' => $validated['body'],
            'recipients_count' => count($sentEmailsList),
            'failed_count' => count($failedRecipients),
            'failed_recipients' => $failedRecipients,
            'recipient_emails' => $sentEmailsList,
            'attachments_meta' => $attachmentsMeta,
            'sent_at' => now(),
        ]);

        AuditService::log(
            action: 'enviar_correo_'.$sendType,
            entity: $sentEmail,
            description: "Correo ({$sentEmail->typeLabel()}) enviado a {$sentEmail->recipients_count} destinatario(s) con {$sentEmail->failed_count} fallo(s): '{$sentEmail->subject}'.",
            properties: [
                'send_type' => $sendType,
                'recipients_count' => $sentEmail->recipients_count,
                'failed_count' => $sentEmail->failed_count,
                'attachments_count' => count($attachmentsMeta),
            ]
        );

        if (count($failedRecipients) > 0 && count($sentEmailsList) === 0) {
            return redirect()->route('admin.emails.index')
                ->with('error', 'No fue posible entregar el correo tras reintentar 3 veces. Revisa los logs del sistema.');
        }

        if (count($failedRecipients) > 0) {
            return redirect()->route('admin.emails.index')
                ->with('info', "Correo enviado a {$sentEmail->recipients_count} destinatario(s). {$sentEmail->failed_count} fallaron tras reintentos.");
        }

        return redirect()->route('admin.emails.index')
            ->with('success', "Correo enviado exitosamente a {$sentEmail->recipients_count} destinatario(s).");
    }

    /**
     * Show detailed view of a sent email record.
     */
    public function show(int $id): View
    {
        $sentEmail = SentEmail::with(['sender', 'recipientUser'])->findOrFail($id);

        return view('admin.emails.show', [
            'sentEmail' => $sentEmail,
        ]);
    }

    /**
     * Store uploaded files and classify them as direct attachments or download links based on size limit.
     *
     * @return array{
     *     0: array<int, array{path: string, name: string, mime: string}>,
     *     1: array<int, array{name: string, size_label: string, url: string}>,
     *     2: array<int, array{name: string, size: int, size_label: string, url: string, delivery_mode: string}>
     * }
     */
    protected function prepareAttachments(Request $request): array
    {
        $files = $request->file('attachments', []);
        if (empty($files)) {
            return [[], [], []];
        }

        $maxDirectBytes = (int) config('mail.attachment_max_bytes', self::MAX_DIRECT_ATTACHMENT_BYTES);
        $totalSize = 0;

        foreach ($files as $file) {
            $totalSize += $file->getSize() ?: 0;
        }

        $sendAsLinks = $totalSize > $maxDirectBytes;
        $attachedFiles = [];
        $downloadLinks = [];
        $meta = [];

        foreach ($files as $file) {
            $originalName = $file->getClientOriginalName();
            $safeName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'-'.Str::random(6).'.'.$file->getClientOriginalExtension();
            $storedPath = $file->storeAs('email-attachments/'.now()->format('Y/m'), $safeName, 'public');
            $fullPath = Storage::disk('public')->path($storedPath);
            $publicUrl = asset('storage/'.$storedPath);
            $size = (int) ($file->getSize() ?: 0);
            $sizeLabel = $size >= 1048576
                ? number_format($size / 1048576, 2).' MB'
                : number_format(max(1, $size / 1024), 1).' KB';

            if ($sendAsLinks) {
                $downloadLinks[] = [
                    'name' => $originalName,
                    'size_label' => $sizeLabel,
                    'url' => $publicUrl,
                ];
                $mode = 'link';
            } else {
                $attachedFiles[] = [
                    'path' => $fullPath,
                    'name' => $originalName,
                    'mime' => $file->getMimeType() ?? 'application/octet-stream',
                ];
                $mode = 'attached';
            }

            $meta[] = [
                'name' => $originalName,
                'size' => $size,
                'size_label' => $sizeLabel,
                'url' => $publicUrl,
                'delivery_mode' => $mode,
            ];
        }

        return [$attachedFiles, $downloadLinks, $meta];
    }
}
