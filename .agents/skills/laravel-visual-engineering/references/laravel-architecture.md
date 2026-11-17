# Referencia: Arquitectura y patrones Laravel

## Antes de implementar
Inspecciona:
- `composer.json` y versión de PHP/Laravel.
- `package.json`, Vite y configuración CSS.
- Rutas y middleware.
- Layouts y componentes Blade existentes.
- Livewire/Inertia/Vue/Alpine/Bootstrap/Tailwind presentes.
- Tests y scripts.

## Reglas
- No mezclar dos sistemas CSS sin necesidad.
- No crear un segundo layout global si ya existe uno adecuado.
- No mover lógica de negocio a Blade.
- No usar JavaScript para sustituir validación o autorización del servidor.
- No crear migraciones para cambios exclusivamente visuales.
- No borrar componentes antiguos hasta comprobar que no se usan.
- Reutilizar rutas nombradas (`route('...')`) en lugar de URLs rígidas.
- Mantener los formularios compatibles con la validación Laravel.
- Evitar consultas N+1 y cargar datos desde controladores/servicios.

## Blade
Usa `{{ $value }}` para escapar salida. Evita `{!! $value !!}` con datos no confiables.
Usa `@csrf` en formularios y `@method` para PUT/PATCH/DELETE.
Utiliza `@error` y `old()` para mostrar validación y conservar entradas.

## Dependencias
Antes de instalar un paquete:
1. Comprueba si ya hay una solución instalada.
2. Comprueba compatibilidad con la versión de Laravel/PHP.
3. Explica por qué hace falta.
4. Evita instalar librerías para efectos que pueden resolverse con CSS.
