CONSEJO COMUNITARIO ZANJÓN DE GARRAPATERO
==========================================

{{ $emailSubject }}

Cordial saludo, {{ $recipientName }}:

{{ $emailBody }}

@if(! empty($downloadLinks))
------------------------------------------
ARCHIVOS DISPONIBLES PARA DESCARGA DIRECTA:
@foreach($downloadLinks as $link)
- {{ $link['name'] }} ({{ $link['size_label'] }}): {{ $link['url'] }}
@endforeach
@endif

------------------------------------------
Atentamente,
{{ $senderName }}
Plataforma Institucional — Zanjón de Garrapatero
