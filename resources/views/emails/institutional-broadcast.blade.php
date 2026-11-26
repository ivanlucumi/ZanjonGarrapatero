<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $emailSubject }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f7f5; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.6;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f8f7f5; padding: 28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 620px; background-color: #ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(78, 52, 46, 0.08); border: 1px solid #e7e2dd;">
                    <tr>
                        <td style="background: linear-gradient(135deg, #2d51ad 0%, #1e3a8a 100%); padding: 24px 28px; border-bottom: 4px solid #f5cd6d;">
                            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #f5cd6d; font-weight: 700; margin-bottom: 4px;">
                                Consejo Comunitario Zanjón de Garrapatero
                            </div>
                            <h1 style="margin: 0; font-size: 20px; font-weight: 700; color: #ffffff;">
                                {{ $emailSubject }}
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 28px;">
                            <p style="margin: 0 0 14px 0; font-size: 15px; color: #3e2723;">
                                Cordial saludo, <strong>{{ $recipientName }}</strong>:
                            </p>
                            <div style="font-size: 14px; color: #334155; white-space: pre-line; line-height: 1.7; background-color: #faf9f7; padding: 18px; border-radius: 10px; border-left: 3px solid #6d4c3d;">
                                {{ $emailBody }}
                            </div>

                            @if(! empty($downloadLinks))
                                <div style="margin-top: 20px; padding: 16px; background-color: #f0f4ff; border: 1px solid #c7d7fe; border-radius: 10px;">
                                    <div style="font-size: 12px; font-weight: 700; text-transform: uppercase; color: #2d51ad; margin-bottom: 8px;">
                                        Archivos Adjuntos (Enlace de Descarga Directa)
                                    </div>
                                    <p style="margin: 0 0 10px 0; font-size: 12px; color: #475569;">
                                        Los siguientes documentos o imágenes superan el límite estándar de adjunto directo y están disponibles para descarga segura:
                                    </p>
                                    @foreach($downloadLinks as $link)
                                        <div style="margin-bottom: 6px;">
                                            <a href="{{ $link['url'] }}" target="_blank" style="display: inline-block; padding: 7px 12px; background-color: #2d51ad; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: 600;">
                                                ⬇ {{ $link['name'] }} ({{ $link['size_label'] }})
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <p style="margin: 22px 0 0 0; font-size: 13px; color: #64748b;">
                                Atentamente,<br>
                                <strong style="color: #3e2723;">{{ $senderName }}</strong><br>
                                Plataforma Institucional — Zanjón de Garrapatero
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
