# Referencia: QA visual y funcional

## Matriz de viewport
| Dispositivo | Ancho CSS |
|---|---:|
| Móvil compacto | 360 px |
| Móvil estándar | 390 px |
| Móvil grande | 430 px |
| Tablet | 768 px |
| Laptop | 1366 px |
| Desktop | 1440 px |

## Revisión visual
Compara la implementación con la referencia:
- altura y alineación del header;
- ancho del contenedor;
- ubicación del H1 y CTA;
- proporción y recorte de imágenes;
- altura de secciones;
- espacios verticales;
- tipografía y saltos de línea;
- bordes, radios y sombras;
- densidad de contenido;
- orden responsive.

## Revisión funcional
- Todos los enlaces navegan al destino correcto.
- Los botones ejecutan acciones reales.
- Menús abren, cierran y funcionan por teclado.
- Formularios validan en servidor y muestran errores.
- Tablas, filtros y paginación funcionan cuando existen.
- Estados loading, empty, error y success son comprensibles.
- No hay errores de consola, red o servidor.

## Criterio de honestidad
Registra qué se probó y qué no se pudo probar. No inventes resultados de tests, navegador, Lighthouse ni comparación visual.
