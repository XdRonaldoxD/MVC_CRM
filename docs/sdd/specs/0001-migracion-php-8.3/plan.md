# Plan: Migración de PHP 7.4 a PHP 8.3

> Etapa 3 del ciclo SDD. Responde **CÓMO** se construye. Basado en evidencia real del código recogida por 3 agentes exploradores (ver sección "Hallazgos").

- **Spec asociada:** specs/0001-migracion-php-8.3/spec.md
- **Estado:** Borrador (pendiente de aprobación del humano)
- **Skill base:** `php-upgrade-74-83`

## Hallazgos del escaneo (evidencia)

Tres agentes escanearon `Controllers/`, `Helpers/`, `models/`, `config/`:

1. **JWT (firebase/php-jwt 3.0.0):** Toda la lógica está centralizada en `Helpers/JwtAuth.php`. Los Controllers (Usuario, Caja, Dashboard, Permiso) la consumen indirectamente vía `checktoken()`/`signup()`. Solo **2 llamadas a `JWT::decode`** (líneas 83 y 130) requieren cambio para 6.x; `encode` no cambia. Impacto **bajo**.
2. **Propiedades dinámicas (deprecadas en 8.2):** **16 clases** (~16%) asignan a `$this->propiedad` sin declararla, casi todas en constructores de Controllers (ej. `$this->fechaactual = date(...)` en `NegocioController`, `AnularDocumentoController`, `PromocionesController`; varias en `Api/PusherController`). Ningún model declara propiedades (usan Eloquent). Impacto **medio**.
3. **Superglobales sin null-safety (deprecado 8.1, TypeError en 8.3):** **~156 accesos** a `$_POST`/`$_GET`/`$_REQUEST`/`$_FILES` sin `??` ni `isset`, repartidos en **26 archivos**. Focos: `NuevoProductoController`, `ProductoExcelController`, `Api/ClienteController`, `UsuarioController`, `CajaController`. Es el **mayor esfuerzo** de la migración.
4. **Limpio:** sin `each()`, `create_function()`, `money_format()`, `utf8_encode/decode()`, interpolación `${}`, ni `@` supresor. No hay bloqueantes de PHP 8.0.

## Enfoque técnico

Migración **incremental y reversible** en rama dedicada, subiendo de escalón (8.0 → 8.1 → 8.2 → 8.3) con tests en verde en cada paso. El orden ataca primero lo que **bloquea** (dependencias), luego lo de **mayor volumen** (superglobales), y deja lo mecánico (JWT, propiedades dinámicas) al final.

Las herramientas hacen el grueso: **Rector** refactoriza superglobales y sintaxis; **PHPCompatibility** diagnostica; **PHPStan** verifica. El trabajo manual se concentra en JWT y en declarar propiedades.

## Archivos afectados

| Archivo / zona | Cambio | Origen |
|----------------|--------|--------|
| `composer.json` | Fijar `php: ^8.3`, `illuminate/database`, subir `php-jwt`→^6, `phpunit`→^10/11 | Fase deps |
| `Helpers/JwtAuth.php` | `use Firebase\JWT\Key;` + adaptar 2 `decode()` a `new Key($key,'HS256')` | Manual |
| 26 archivos con superglobales | `$_POST['x']` → `$_POST['x'] ?? null` (Rector + revisión) | Mayoría |
| 16 clases con props dinámicas | Declarar propiedades (preferido) o `#[\AllowDynamicProperties]` | Manual/Rector |
| `rector.php`, `phpstan.neon` (nuevos) | Config de herramientas | Setup |

## Datos / base de datos
- **Sin cambios de esquema.** La migración es de runtime/código, no de datos.
- **Rollback:** todo en rama `migracion-php83`; revertir = volver a `main`. Un commit por escalón permite `git bisect`.

## Dependencias / herramientas
- Añadir (dev): `rector/rector ^2`, `squizlabs/php_codesniffer` + `phpcompatibility/php-compatibility`, `phpstan/phpstan`.
- Actualizar (prod): `firebase/php-jwt ^6`, `illuminate/database` (fijar a la rama en uso), `phpunit ^10/11`.
- Comandos exactos y config: ver skill `php-upgrade-74-83` (`references/tooling.md` y `references/dependencies.md`).

## Riesgos y mitigaciones

| Riesgo | Mitigación |
|--------|-----------|
| Los 156 accesos a superglobales rompen flujos en runtime | Rector automatiza el `?? null`; luego prueba de humo en login/productos/caja |
| Salto JWT 3.x→6.x rompe autenticación | Cambio aislado en 1 archivo; probar login + checktoken antes de seguir |
| `illuminate/database: *` arrastra versión incompatible | Fijar versión exacta antes del `update`; usar dry-run |
| `cyber-duck/laravel-excel` bloquea a Laravel 10/11 | Evaluar en dry-run si sigue en uso; retirar si no |
| PHPUnit 10+ cambia formato de config | Revisar los 2 tests (`PruebaTest`, `AnularDocumentoTest`) al subir |

## Cómo se verificará (mapeo a criterios de aceptación de la spec)

| Criterio | Cómo se comprueba |
|----------|-------------------|
| CA1 (composer install en 8.3) | `composer install` con PHP 8.3 sin errores |
| CA2 (tests ≥ baseline) | `phpunit` en 8.3 vs baseline capturado en 7.4 |
| CA3 (flujos críticos sin Deprecated/Fatal) | Prueba de humo: login, PDF/CPE, MySQL; revisar logs |
| CA4 (sin props dinámicas sin declarar) | Reporte PHPCompatibility limpio en ese rubro |
| CA5 (PHPStan nivel 5 sin nuevos errores) | `phpstan analyse --level=5` |
| CA6 (deps fijas y compatibles) | Revisar `composer.json`: sin `*`, versiones acotadas |

## Orden de ejecución propuesto (resumen → detalle en tasks.md)

1. Pre-vuelo: rama + baseline de tests en 7.4.
2. **Dependencias** (desbloquea todo): fijar y actualizar `composer.json`.
3. **Diagnóstico** PHPCompatibility → reporte.
4. **Rector incremental** 8.0→8.3 (automatiza superglobales y sintaxis), tests por escalón.
5. **Manual:** JWT 6.x (1 archivo) + propiedades dinámicas (16 clases).
6. **Verificación:** PHPStan + suite completa + prueba de humo.
