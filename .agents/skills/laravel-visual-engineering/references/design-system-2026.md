# Referencia: Design System 2026 para Laravel

Esta guía complementa `SKILL.md`. Se aplica solo cuando respeta la marca y la referencia.

## Tokens CSS de ejemplo

Adapta los valores a la referencia. No copies esta paleta de forma automática.

```css
:root {
  --color-brand: #3157d5;
  --color-brand-strong: #2444b5;
  --color-accent: #8b5cf6;
  --color-bg: #f7f8fc;
  --color-surface: #ffffff;
  --color-surface-glass: rgb(255 255 255 / 72%);
  --color-text: #172033;
  --color-muted: #667085;
  --color-border: rgb(23 32 51 / 10%);

  --radius-sm: .5rem;
  --radius-md: .875rem;
  --radius-lg: 1.25rem;
  --radius-xl: 1.75rem;
  --radius-pill: 999px;

  --shadow-soft: 0 8px 30px rgb(16 24 40 / 7%);
  --shadow-floating: 0 20px 60px rgb(16 24 40 / 13%);

  --container:  min(100% - 3rem,  min( min(100% - 3rem, 80rem), 80rem));
  --motion-fast: 180ms;
  --motion-normal: 280ms;
  --ease-standard: cubic-bezier(.2, .8, .2, 1);
}
```

Corrige o reemplaza cualquier token que no corresponda al proyecto. La paleta de ejemplo no es una recomendación universal.

## Reglas para glass UI

- Usa superficies translúcidas solo donde exista contraste suficiente.
- Incluye fondo de respaldo cuando `backdrop-filter` no esté disponible.
- Evita glass en bloques extensos de texto.
- Mantén bordes sutiles y blur moderado.
- No sacrifiques contraste por estética.

## Botones

Define variantes coherentes:
- Primary: acción principal.
- Secondary: acción secundaria.
- Ghost: acciones discretas.
- Destructive: acciones de riesgo.

Todos los botones deben tener hover, active, focus-visible, disabled y estados de carga si realizan operaciones asíncronas.

## Movimiento

Prefiere transiciones de `opacity` y `transform`. No animes dimensiones grandes si provoca layout thrashing. Respeta `prefers-reduced-motion`.
