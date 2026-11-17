<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta de Error del Sistema</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1e293b; line-height: 1.5;">
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f5f9; padding: 24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 650px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td style="background: linear-space, linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 24px 28px; border-bottom: 4px solid #ef4444;">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td>
                                        <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1.5px; color: #fbbf24; font-weight: 700; margin-bottom: 4px;">
                                            Consejo Comunitario Zanjón de Garrapatero
                                        </div>
                                        <h1 style="margin: 0; font-size: 20px; font-weight: 800; color: #ffffff; display: flex; align-items: center;">
                                            ⚠️ Notificación de Error del Sistema
                                        </h1>
                                    </td>
                                    <td align="right" valign="middle">
                                        <div style="display: inline-block; background-color: #dc2626; color: #ffffff; font-size: 13px; font-weight: 800; padding: 6px 14px; border-radius: 9999px; letter-spacing: 0.5px;">
                                            CÓDIGO: {{ $errorData['errorCode'] }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 28px;">
                            
                            <!-- Notice box -->
                            <div style="background-color: #fef2f2; border: 1px solid #fee2e2; border-left: 4px solid #dc2626; border-radius: 8px; padding: 16px; margin-bottom: 24px;">
                                <div style="font-size: 12px; font-weight: 700; color: #991b1b; text-transform: uppercase; margin-bottom: 4px;">
                                    Excepción no controlada registrada en el servidor
                                </div>
                                <div style="font-size: 15px; font-weight: 700; color: #7f1d1d; word-break: break-word;">
                                    {{ $errorData['errorMessage'] ?: 'Sin mensaje descriptivo provisto por la excepción.' }}
                                </div>
                                <div style="font-size: 12px; color: #b91c1c; margin-top: 6px; font-family: monospace;">
                                    {{ $errorData['exceptionClass'] }}
                                </div>
                            </div>

                            <!-- Details Table -->
                            <div style="margin-bottom: 24px;">
                                <h3 style="margin: 0 0 12px 0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; font-weight: 700;">
                                    📌 Datos del Contexto de Ejecución
                                </h3>
                                
                                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse; font-size: 13px;">
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; width: 35%; background-color: #f8fafc;">Código de Error:</td>
                                        <td style="padding: 10px 12px; font-weight: 700; color: #b91c1c;">
                                            <span style="background-color: #fee2e2; color: #991b1b; padding: 2px 8px; border-radius: 4px; font-family: monospace;">{{ $errorData['errorCode'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Ruta / URL afectada:</td>
                                        <td style="padding: 10px 12px; font-family: monospace; word-break: break-all; color: #0f172a;">
                                            <span style="display: inline-block; background-color: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-size: 11px; margin-right: 4px; font-weight: bold;">{{ $errorData['method'] }}</span>
                                            {{ $errorData['url'] }}
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Archivo y Línea:</td>
                                        <td style="padding: 10px 12px; font-family: monospace; font-size: 12px; color: #334155; word-break: break-all;">
                                            {{ $errorData['file'] }}:{{ $errorData['line'] }}
                                        </td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Dirección IP Cliente:</td>
                                        <td style="padding: 10px 12px; font-family: monospace; color: #0f172a;">{{ $errorData['ip'] }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Usuario / Sesión:</td>
                                        <td style="padding: 10px 12px; color: #0f172a;">{{ $errorData['user'] ?? 'No autenticado / Invitado' }}</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #e2e8f0;">
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Entorno:</td>
                                        <td style="padding: 10px 12px; text-transform: uppercase; font-weight: 600; color: #0f172a;">{{ $errorData['environment'] }}</td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px 12px; font-weight: 600; color: #64748b; background-color: #f8fafc;">Fecha y Hora:</td>
                                        <td style="padding: 10px 12px; color: #0f172a;">{{ $errorData['timestamp'] }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Stack trace box -->
                            <div>
                                <h3 style="margin: 0 0 8px 0; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; font-weight: 700;">
                                    🔍 Traza de Ejecución (Stack Trace)
                                </h3>
                                <div style="background-color: #0f172a; border-radius: 8px; padding: 14px; overflow-x: auto; max-height: 280px;">
                                    <pre style="margin: 0; font-family: Consolas, 'Fira Code', Monaco, monospace; font-size: 11px; line-height: 1.6; color: #e2e8f0; white-space: pre-wrap; word-break: break-word;">{{ $errorData['trace'] }}</pre>
                                </div>
                            </div>

                            <!-- Footer note -->
                            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; font-size: 12px; color: #64748b; text-align: center;">
                                Este mensaje ha sido generado automáticamente por el núcleo de monitoreo y auditoría del Consejo Comunitario Zanjón de Garrapatero. Los detalles también han sido registrados en <code>storage/logs/laravel.log</code>.
                            </div>

                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
