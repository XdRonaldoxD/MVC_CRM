# Plan: Control de venta de silicona por onza

> Etapa 3 del ciclo SDD. CÓMO se construye. Spec aprobada el 2026-06-25. Enfoque A: cada sabor = un producto con unidad ONZA, reutilizando el flujo de ventas/caja existente.

- **Spec asociada:** specs/0002-control-venta-silicona-onza/spec.md
- **Estado:** Borrador (pendiente de aprobación del humano)
- **Stack:** Back PHP 7.4.23 + MySQL `farxcfyq_boticarosa`; Front Angular 13 / Node 14.

## Enfoque técnico

El sistema ya vende por cantidad decimal, descuenta stock y registra en caja. No se inventa nada nuevo: se **dan de alta los datos** (unidad ONZA + 5 productos de sabor) para que la silicona se venda por el flujo normal, y se agrega una **sección de menú "Silicona"** en el front para que la venta y el control sean cómodos y enfocados.

Se construye en 3 fases incrementales: primero los datos (ya vendible por el flujo actual), luego el reporte de control, y al final la sección de menú dedicada. Cada fase deja algo probable en local.

## Archivos / componentes afectados

**Backend (`MVC_CRM`):**
| Archivo / acción | Cambio |
|------------------|--------|
| Tabla `unidad` (SQL) | `INSERT` de la unidad "ONZA" |
| Tabla `producto` (+ stock por bodega) | Alta de 5 productos (un sabor c/u), unidad ONZA, precio 0.50, stock en onzas |
| Sistema de permisos | Alta del módulo/permiso "SILICONA" |
| Controller de reporte (nuevo o reusar `ReporteVentaProductoController`) | Acción que devuelva onzas vendidas y stock por sabor en un rango |
| Script SQL versionado en `sql/` | Para reproducir el alta (datos semilla) |

**Frontend (`administrador_mvc`):**
| Archivo | Cambio |
|---------|--------|
| `src/app/pages/sidebar/sidebar.component.html` | Nueva entrada de menú "SILICONA" (con permiso) |
| `src/app/pages/pages.routes.ts` | Ruta `/Silicona` con guard de permiso |
| `src/app/pages/pages.module.ts` | Declarar el nuevo componente |
| `src/app/pages/silicona/` (nuevo) | Componente: venta rápida + consulta por sabor |
| `src/app/services/silicona.service.ts` (nuevo) | Llamadas a la API (patrón de `producto.service.ts`) |

## Datos / base de datos

- **Sin cambios de esquema.** Solo `INSERT` de datos (unidad + productos). `cantidad_negocio_detalle`, `stock` y `precio` ya son `decimal` → soportan onzas y el precio 0.50.
- Onzas enteras: el front validará que la cantidad sea entero ≥ 1.
- **Rollback:** los `INSERT` se hacen en script reversible; revertir = borrar esos registros (o marcarlos `vigente=0`).

## Riesgos y mitigaciones

| Riesgo | Mitigación |
|--------|-----------|
| **IVA/afectación altera el 0.50** — `NegocioController` divide el precio entre 1.18 si el producto está marcado afecto a IVA (código 10) | Crear los productos de silicona con el **tipo de afectación correcto (exento)** para que 0.50 quede exacto. Verificar con una venta de prueba en local |
| El permiso "SILICONA" no aparece en el menú | Registrar el permiso en BD y asignarlo a los perfiles (vendedora + admin) |
| Stock por sabor mal inicializado | Cargar stock inicial al alta; validar que la venta lo descuente correctamente |
| Productos requieren bodega/categoría | Asociarlos a la bodega y categoría existentes igual que cualquier producto |

## Cómo se verificará (mapeo a criterios de aceptación)

| Criterio | Cómo se comprueba |
|----------|-------------------|
| CA1 (unidad ONZA) | `SELECT * FROM unidad WHERE glosa_unidad='ONZA'` |
| CA2 (5 productos sabor) | `SELECT` de los 5 productos con su unidad y precio 0.50 |
| CA3 (venta por onza, total lineal) | Venta de prueba de 3 onzas → total = S/1.50 exacto |
| CA4 (descuenta stock + caja) | Verificar `stock` antes/después y el registro en caja/comprobante |
| CA5 (sección de menú) | La opción "Silicona" aparece y la página carga con permiso |
| CA6 (reporte por sabor) | La consulta devuelve onzas vendidas y stock por sabor en un rango |

## Orden de ejecución propuesto (detalle en tasks.md)

1. **Pre-vuelo:** rama `feature/silicona-onza` + respaldo de la BD.
2. **Fase datos:** unidad ONZA + 5 productos (con afectación exenta) + stock inicial. Probar venta por el flujo actual.
3. **Fase reporte:** endpoint de onzas/stock por sabor.
4. **Fase front:** permiso + menú + ruta + página de venta/consulta.
5. **Verificación:** venta de prueba 3 oz = S/1.50, stock descontado, reporte correcto.
