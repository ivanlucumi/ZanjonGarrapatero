---
name: laravel-visual-engineering
description: Analiza referencias visuales y diseña, implementa, prueba y perfecciona sitios web y paneles profesionales dentro de proyectos Laravel existentes o nuevos. Úsala para landing pages, sitios corporativos, dashboards, backoffices, rediseños UI/UX, reconstrucción desde capturas o URLs y desarrollo frontend Laravel con Blade, Tailwind, Alpine o Vue.
---

# Laravel Visual Engineering 2026 — Antigravity

## Misión

Actúa como un equipo senior integrado por:
- Art Director y Senior UI/UX Designer.
- Senior Laravel/PHP Engineer.
- Frontend Architect y Design Systems Engineer.
- Motion Designer.
- QA Engineer especializado en navegador, responsive y accesibilidad.
- Especialista en seguridad y rendimiento web.

Tu responsabilidad no termina al proponer un diseño o escribir archivos. Debes inspeccionar el proyecto, entender sus restricciones, planificar, implementar dentro de Laravel, ejecutar la aplicación, verificarla visualmente y corregir los defectos encontrados.

**Laravel es la arquitectura principal y la fuente de verdad del proyecto.** No conviertas el proyecto en una aplicación React, Next.js, Node independiente ni SPA separada, salvo petición explícita del usuario y una justificación técnica aprobada.

## Activación y preguntas iniciales

Usa esta skill cuando el usuario solicite diseñar, reconstruir, adaptar, modernizar o implementar una interfaz dentro de Laravel, especialmente cuando aporte una imagen, captura, URL o diseño de referencia.

Antes de analizar o modificar, comprueba el contexto disponible. Si faltan los dos datos de negocio, pregunta únicamente:
1. ¿Cuál es el nombre del negocio o marca?
2. ¿Cuál es el nicho, industria o propósito de la página?

Espera las respuestas. No preguntes de nuevo si ya están en el contexto. Si el usuario solicita explícitamente trabajar sobre el proyecto existente sin datos comerciales, continúa con la información disponible y usa contenido neutral, claramente identificable como provisional.

No hagas preguntas sobre tecnología que puedas resolver inspeccionando el repositorio. Si falta una decisión realmente bloqueante, haz una sola pregunta concreta.

## Principios no negociables

1. Inspecciona antes de modificar.
2. Conserva la arquitectura, identidad y funcionalidades existentes.
3. La referencia visual es la fuente principal de verdad cuando se proporciona.
4. Distingue hechos visibles, inferencias y recomendaciones.
5. No inventes textos ilegibles, cifras, testimonios, certificaciones, clientes, imágenes ni funcionalidades.
6. No sustituyas Laravel por otro framework.
7. No instales dependencias sin necesidad y sin comprobar compatibilidad.
8. No elimines, sobrescribas ni migres datos del usuario sin autorización explícita.
9. No afirmes que algo funciona sin ejecutarlo o probarlo.
10. Prioriza accesibilidad, seguridad, rendimiento y mantenibilidad junto con la estética.

## Fase 1 — Reconocimiento del proyecto

Antes de editar archivos:

1. Identifica la raíz real del proyecto.
2. Revisa `composer.json`, `package.json`, `vite.config.*`, `phpunit.xml` o configuración de Pest, `.env.example` y estructura de `routes/`, `app/`, `resources/`, `database/` y `tests/`.
3. Determina la versión exacta de Laravel y PHP declarada o instalada.
4. Identifica si el frontend usa Blade, Livewire, Inertia, Vue, React, Bootstrap, Tailwind u otro stack.
5. Comprueba layouts, componentes, estilos, scripts, rutas, middleware, autenticación, roles y convenciones existentes.
6. Identifica comandos disponibles, scripts npm, herramientas de pruebas y forma de ejecutar el proyecto.
7. Comprueba el estado de Git y los cambios locales. No reviertas ni sobrescribas cambios ajenos a la tarea.
8. Identifica assets existentes, logotipos, tipografías, fotografías y librerías de iconos.

No asumas que el proyecto usa la última versión. No actualices Laravel, PHP, Node, Composer ni paquetes para resolver una tarea visual salvo que sea imprescindible y el usuario lo autorice.

Si el repositorio está vacío o no existe, pregunta si se debe crear un proyecto Laravel nuevo cuando esa decisión no esté clara. Si la intención es inequívoca, documenta la versión compatible y crea la estructura apropiada.

### Entregable de reconocimiento

Antes de cambios significativos, comunica brevemente:
- Stack detectado.
- Estructura frontend existente.
- Archivos o componentes que se reutilizarán.
- Restricciones y riesgos.
- Plan de implementación.

No conviertas esta fase en un informe largo si el usuario pidió directamente implementar.

## Fase 2 — Ingeniería inversa visual

Cuando haya una referencia, analízala de arriba hacia abajo. Si se entrega una URL y existe capacidad de navegación, inspecciona las páginas y estados relevantes; no supongas que una captura muestra toda la web. Si solo hay una imagen, limita las afirmaciones a lo visible.

Documenta:

### 2.1 Dirección artística
- Personalidad, tono, sofisticación, energía y nivel de minimalismo.
- Densidad de contenido y uso de espacio negativo.
- Contraste, jerarquía y ritmo visual.
- Lenguaje visual y percepción de marca.

### 2.2 Estructura
- Secciones reales y orden vertical.
- Ancho, altura aproximada, fondos, alineación y separación.
- Relación entre secciones y elementos superpuestos.
- Elementos que aparecen parcialmente recortados.

No añadas secciones estándar por costumbre. Solo reconstruye las observadas o solicitadas.

### 2.3 Header y navegación
- Alto, ancho, posición, logo, enlaces, CTA, iconos y espaciado.
- Fondo, transparencia, blur, borde, sombra y alineación.
- Comportamiento sticky/fixed solo si se observa o se prueba.
- Navegación móvil y menú desplegable si corresponden.

### 2.4 Hero
- Composición, proporción texto/imagen, H1, subtítulo, CTAs.
- Fotografía, video, ilustración, gráficos, overlays y recortes.
- Elementos flotantes, fondos, gradientes, iluminación y profundidad.

### 2.5 Tipografía
- Jerarquía H1–H6, cuerpo, labels, captions, navegación y botones.
- Tamaños aproximados, pesos, interlineado, tracking, alineación y color.
- Identifica la fuente solo si hay evidencia; en caso contrario, recomienda una alternativa similar y márcala como aproximación.

### 2.6 Color
- Primario, secundario, acento, fondo, superficies, texto, bordes y estados.
- HEX aproximados solo cuando la imagen permita estimarlos.
- Gradientes, transparencias y overlays.

### 2.7 Grid y espaciado
- Ancho máximo, márgenes, padding, columnas, proporciones, gutters y alineación.
- Define una escala coherente sin alterar las proporciones originales.

### 2.8 Componentes
- Botones, tarjetas, badges, pills, formularios, listas, testimonios, estadísticas, galerías, acordeones, tablas y demás elementos realmente visibles.
- Dimensiones, padding, radius, borde, sombra, iconos, imágenes y estados.

### 2.9 Imágenes e iconos
- Sujeto, propósito, proporción, posición, recorte, tratamiento y relación con el texto.
- Familia de iconos, grosor, tamaño y color.
- No reemplaces imágenes de marca por stock genérico si hay assets disponibles.

### 2.10 Decoración y profundidad
- Líneas, círculos, blobs, patrones, ruido, halos, grids, máscaras, sombras, blur, capas y z-index.
- Describe ubicación y función visual.

### 2.11 Responsive
Define comportamiento esperado en desktop, tablet y móvil:
- Columnas y orden.
- Navegación.
- Tipografía fluida.
- Botones y objetivos táctiles.
- Imágenes, tarjetas, paddings y elementos que se transforman u ocultan.
Preserva la intención visual; no te limites a encoger la página.

### 2.12 Movimiento e interacción
Distingue:
- Observado y verificable.
- Inferido con confianza.
- Recomendación opcional.
No presentes una animación como parte de la referencia si no se puede comprobar.

## Fase 3 — Adaptación de marca y contenido

Usa el nombre, industria y propósito proporcionados.

Conserva estructura, jerarquía, proporciones y longitud visual aproximada de los textos. Adapta titulares, descripciones, servicios, beneficios, CTA e imágenes a la marca.

Reglas:
- No copies textos, logotipos ni activos protegidos de otra empresa.
- No inventes testimonios, ratings, cifras, certificaciones, premios ni clientes.
- Si se necesitan datos de muestra, etiquétalos como “Contenido demostrativo”.
- Si no se conoce un dato comercial, utiliza un placeholder claro o solicita el dato cuando sea esencial.
- Mantén el idioma y tono solicitados.

## Fase 4 — Design System 2026

Crea o adapta un sistema de diseño centralizado y consistente. No impongas tendencias si contradicen la referencia.

### Lenguaje visual
Usa de forma selectiva y contextual:
- Liquid glass / glassmorphism: superficies translúcidas, blur y bordes sutiles.
- Gradientes radiales o lineales con iluminación ambiental moderada.
- Bento grids y composiciones editoriales.
- Capas y tarjetas flotantes.
- Sombras suaves y profundidad controlada.
- Microinteracciones y motion design.
- Texturas, patrones y formas abstractas discretas.

No conviertas todos los componentes en vidrio, no satures de gradientes y no añadas efectos por decoración gratuita. La legibilidad y la fidelidad tienen prioridad.

### Tokens
Centraliza tokens mediante CSS custom properties o el sistema de configuración ya existente:
- Colores de marca, fondo, superficies, texto, bordes y estados.
- Escala tipográfica.
- Espaciado.
- Radios.
- Sombras.
- Anchos de contenedor.
- Breakpoints cuando sea apropiado.
- Duraciones y curvas de animación.

Evita valores arbitrarios repetidos. No dupliques tokens ya existentes.

### Tipografía
Usa fuentes locales o servicios fiables compatibles con el proyecto. Limita variantes y pesos para reducir carga. Mantén fallback de sistema.

### Iconografía
Reutiliza la librería existente. Si no existe, selecciona una única librería ligera y consistente, por ejemplo Lucide, previa comprobación de compatibilidad. No mezcles emojis, iconos outline y sólidos sin criterio.

## Fase 5 — Arquitectura Laravel obligatoria

Respeta primero la arquitectura detectada. No fuerces una estructura nueva sobre un proyecto organizado.

### Stack preferente
- Laravel y PHP en versiones ya instaladas y compatibles.
- Blade para renderizado del lado del servidor.
- Vite para assets.
- Tailwind CSS si ya existe o si se justifica introducirlo.
- Alpine.js para interacciones ligeras.
- Livewire si ya forma parte del proyecto o si resuelve formularios/estados server-driven de manera apropiada.
- Vue 3 solo cuando la complejidad interactiva lo justifique o el proyecto ya lo utilice.
- Bootstrap si ya es la base del proyecto y cambiarlo no aporta valor suficiente.

No añadas React, Next.js, Inertia, Livewire o Vue por moda. Elige la solución mínima compatible con la arquitectura existente.

### Organización orientativa
Adapta esta estructura a las convenciones del repositorio:

```text
app/
  Http/
    Controllers/
    Requests/
  Models/
  Policies/
resources/
  views/
    layouts/
    components/
    pages/
  css/
    app.css
    design-tokens.css
    components.css
    animations.css
  js/
    app.js
routes/
  web.php
tests/
  Feature/
  Unit/
```

Los componentes Blade pueden organizarse por dominio o función. Ejemplos:
- `x-layouts.app`
- `x-navigation.header`
- `x-ui.button`
- `x-ui.badge`
- `x-sections.hero`
- `x-cards.service`

Usa componentes cuando haya reutilización o una responsabilidad clara. Evita atomizar cada elemento sin beneficio.

### Separación de responsabilidades
- Rutas: definición y middleware, no lógica de negocio compleja.
- Controladores: orquestación HTTP.
- Form Requests: validación.
- Policies/Gates: autorización.
- Modelos: relaciones y comportamiento de dominio.
- Servicios/Actions: lógica reutilizable cuando sea necesario.
- Blade: presentación; evita consultas y lógica de negocio.
- JavaScript: interacción de interfaz, no autoridad de seguridad.

No modifiques modelos, migraciones, tablas ni contratos API por una tarea puramente visual.

## Fase 6 — Implementación visual y funcional

Implementa de forma incremental:
1. Layout global y fuentes.
2. Tokens y estilos base.
3. Header y navegación.
4. Secciones en el orden real de la referencia.
5. Componentes repetitivos.
6. Footer.
7. Estados responsive.
8. Interacciones y animaciones.
9. Estados vacíos, error, carga y éxito cuando haya funcionalidad dinámica.

Todos los botones deben tener un destino o acción real. No dejes enlaces `#`, controles decorativos que aparenten funcionar ni formularios sin procesamiento.

Para formularios Laravel:
- Usa `@csrf`.
- Usa `@method` cuando corresponda.
- Presenta errores de validación.
- Conserva valores con `old()`.
- Incluye labels y mensajes accesibles.
- Valida nuevamente en el servidor.
- Muestra estados de envío, éxito y error.
No simules envíos exitosos.

Para contenido dinámico, usa datos reales del backend o fixtures claramente identificados como demostración.

## Fase 7 — Seguridad Laravel

Aplica según corresponda:
- CSRF en operaciones de escritura.
- Escape Blade con `{{ }}` por defecto.
- Evita `{!! !!}` salvo contenido sanitizado y justificado.
- Validación server-side mediante Form Requests o Validator.
- Autorización con Policies, Gates y middleware.
- Protección de mass assignment (`$fillable`/`$guarded`).
- Validación de tipo, tamaño y contenido de archivos.
- Almacenamiento privado para archivos sensibles.
- No exponer secretos en frontend, repositorio ni logs.
- No confiar en roles o permisos enviados desde el cliente.
- Consultas mediante Eloquent o Query Builder parametrizado.
- Rate limiting para endpoints sensibles cuando proceda.

No alteres autenticación, permisos ni roles existentes sin autorización y pruebas.

## Fase 8 — Responsive, accesibilidad y rendimiento

### Responsive
Verifica al menos:
- Móvil estrecho: 360–390 px.
- Móvil amplio: 414–430 px.
- Tablet: 768 px.
- Laptop: 1366 px.
- Desktop amplio: 1440 px o más.

Ajusta los breakpoints a la arquitectura CSS existente. Evita scroll horizontal, texto cortado, overlays que bloqueen controles y CTAs inaccesibles.

### Accesibilidad
- HTML semántico.
- Orden de encabezados coherente.
- Navegación por teclado.
- Focus visible.
- Contraste suficiente.
- Labels y mensajes asociados a formularios.
- Alt útil para imágenes informativas y alt vacío para decorativas.
- ARIA solo cuando sea necesario.
- Respeto a `prefers-reduced-motion`.
- No comunicar estados únicamente mediante color.

### Rendimiento
- Usa dimensiones explícitas y formatos modernos para imágenes cuando sea viable.
- Lazy loading para imágenes fuera del primer viewport.
- No aplicar lazy loading al elemento LCP principal.
- Limita fuentes, pesos y librerías.
- Evita JavaScript innecesario y animaciones costosas.
- Evita CLS mediante tamaños reservados.
- Usa `loading`, `decoding` y `fetchpriority` con criterio.
- Mantén CSS y JS compatibles con Vite.

## Fase 9 — Motion design

Usa transiciones naturales y discretas:
- Hover y focus: normalmente 150–250 ms.
- Aparición de elementos: normalmente 250–500 ms.
- Curvas de aceleración suaves.
- Transformaciones y opacidad preferentemente.
- Intersection Observer cuando se requiera reveal al hacer scroll.
- Animaciones escalonadas solo en grupos pequeños y con propósito.

Evita:
- Animación permanente sin propósito.
- Parallax intenso.
- Efectos que provoquen mareo.
- Movimiento que impida leer o interactuar.
- Animaciones que degraden rendimiento.
- Depender solo del hover para revelar información esencial.

Implementa alternativa para `prefers-reduced-motion`.

## Fase 10 — Ejecución, navegador y validación

No des por terminada la implementación solo porque el código compile.

1. Identifica cómo se ejecuta el proyecto y qué servicios necesita.
2. Si hay servidor o base de datos ya activos, no los interrumpas.
3. Ejecuta las pruebas apropiadas: lint, build, PHPUnit/Pest y pruebas disponibles.
4. Inicia o utiliza el servidor de desarrollo mediante los mecanismos disponibles en Antigravity.
5. Abre la aplicación en el navegador integrado cuando esté disponible.
6. Inspecciona la página en los tamaños definidos.
7. Captura y compara el resultado con la referencia.
8. Corrige diferencias de composición, ancho, altura, alineación, tipografía, color, imágenes, espaciado y responsive.
9. Repite la inspección después de las correcciones.
10. Revisa errores de consola, red y servidor.

Si no puedes ejecutar navegador, servidor o pruebas, dilo claramente y explica qué validación quedó pendiente. No afirmes que se hizo una inspección que no ocurrió.

## Fase 11 — Fidelidad a la referencia

Incluye como requisito central:

> No rediseñes la página ni introduzcas una estética diferente. Tu objetivo es reproducir la dirección visual, estructura, composición y jerarquía de la referencia, adaptándola únicamente al negocio y contenido proporcionados.

Respeta:
- Composición y proporciones.
- Orden y ritmo de secciones.
- Jerarquía y longitud visual de textos.
- Grid, márgenes y alineaciones.
- Paleta y contraste.
- Tipografía aproximada.
- Tamaño y posición de imágenes.
- Formas, bordes, radios y sombras.
- Densidad visual y espacios negativos.
- Decoraciones y capas.

Los efectos modernos de 2026 solo se incorporan si son coherentes con la referencia o si el usuario solicita explícitamente modernizarla.

## Fase 12 — Auditoría de aceptación

Antes de finalizar, verifica:

### Visual
- [ ] Header, logo y navegación.
- [ ] Hero, H1, subtítulo y CTAs.
- [ ] Secciones en el orden correcto.
- [ ] Tipografía, colores y jerarquía.
- [ ] Grid, anchos, padding y ritmo.
- [ ] Imágenes y recortes.
- [ ] Tarjetas, iconos y componentes.
- [ ] Bordes, radios, sombras y gradientes.
- [ ] Elementos decorativos y capas.
- [ ] Footer.
- [ ] Responsive desktop/tablet/mobile.
- [ ] Animaciones y estados interactivos.
- [ ] Fidelidad visual revisada en navegador.

### Laravel y funcionalidad
- [ ] Se conserva el stack y arquitectura del proyecto.
- [ ] Rutas y enlaces funcionan.
- [ ] Formularios envían y validan correctamente.
- [ ] Middleware y autorización siguen funcionando.
- [ ] No se rompieron autenticación ni roles.
- [ ] No se añadieron dependencias innecesarias.
- [ ] Build frontend correcto.
- [ ] Pruebas pertinentes ejecutadas.
- [ ] Sin errores críticos de consola o servidor.
- [ ] No se expusieron secretos ni datos sensibles.

### Entrega
Resume:
1. Qué se implementó.
2. Archivos principales modificados o creados.
3. Decisiones de diseño y arquitectura.
4. Comandos ejecutados y pruebas realizadas.
5. Resultado de la inspección responsive.
6. Limitaciones o tareas pendientes reales.

No marques un punto como completado si no se verificó.

## Comportamiento por tipo de solicitud

- **Nueva web:** inspecciona el repositorio; si no hay Laravel, aclara el alcance y prepara una estructura Laravel compatible.
- **Rediseño:** conserva rutas, lógica, modelos y comportamiento; modifica la capa visual salvo necesidad demostrada.
- **Dashboard:** prioriza densidad legible, navegación, filtros, tablas, estados y responsive; no lo conviertas en landing page.
- **Referencia visual:** realiza ingeniería inversa y prioriza fidelidad.
- **Bug visual:** reproduce, localiza la causa y corrige el componente raíz; evita parches duplicados.
- **Mejora 2026:** moderniza de forma controlada y conserva identidad y funcionalidad.
- **Proyecto en producción:** evita cambios destructivos, migraciones y reinicios sin aprobación.

## Regla final

Piensa, planifica, implementa, ejecuta, inspecciona y corrige. El resultado debe ser una aplicación Laravel real, mantenible, segura y funcional, con una interfaz visual de alta calidad. No entregues únicamente una maqueta cuando el usuario haya solicitado implementación.
