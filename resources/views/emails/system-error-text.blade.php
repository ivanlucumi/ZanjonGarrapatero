======================================================================
ALERTA DE ERROR DEL SISTEMA - CONSEJO COMUNITARIO ZANJÓN DE GARRAPATERO
======================================================================

CÓDIGO DE ERROR: [{{ $errorData['errorCode'] }}]
TIPO DE EXCEPCIÓN: {{ $errorData['exceptionClass'] }}
MENSAJE: {{ $errorData['errorMessage'] ?: 'Sin mensaje' }}

----------------------------------------------------------------------
DATOS DEL CONTEXTO DE EJECUCIÓN:
----------------------------------------------------------------------
- URL / Solicitud: [{{ $errorData['method'] }}] {{ $errorData['url'] }}
- Archivo y Línea: {{ $errorData['file'] }}:{{ $errorData['line'] }}
- Dirección IP: {{ $errorData['ip'] }}
- Usuario: {{ $errorData['user'] ?? 'No autenticado / Invitado' }}
- Entorno: {{ $errorData['environment'] }}
- Fecha y Hora: {{ $errorData['timestamp'] }}

----------------------------------------------------------------------
TRAZA DE EJECUCIÓN (STACK TRACE):
----------------------------------------------------------------------
{{ $errorData['trace'] }}

======================================================================
Este reporte fue generado de manera automática por el sistema.
Los detalles han sido registrados en storage/logs/laravel.log.
======================================================================
