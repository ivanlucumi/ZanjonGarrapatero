<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Control de Dominio Permitido (Protección Anti-Clonación)
    |--------------------------------------------------------------------------
    |
    | Registra aquí los dominios autorizados para ejecutar la plataforma.
    | Si la aplicación se abre desde cualquier otro dominio distinto, la
    | pantalla se bloqueará completamente en negro sin permitir acciones.
    |
    */

    'enabled' => env('ALLOWED_DOMAIN_CHECK_ENABLED', true),

    'domains' => array_values(array_unique(array_merge(
        [
            'consejozanjondegarrapatero.org',
            'www.consejozanjondegarrapatero.org',
        ],
        array_filter(array_map(
            static function (string $entry): string {
                $entry = strtolower(trim($entry));
                if ($entry === '') {
                    return '';
                }

                if (str_contains($entry, '://')) {
                    $host = parse_url($entry, PHP_URL_HOST);

                    return is_string($host) ? $host : '';
                }

                return explode(':', rtrim($entry, '/'))[0];
            },
            explode(',', (string) env('ALLOWED_DOMAINS', 'localhost,127.0.0.1,zanjongarrapatero.test,zanjongarrapatero.gov.co,www.zanjongarrapatero.gov.co'))
        ))
    ))),

];
