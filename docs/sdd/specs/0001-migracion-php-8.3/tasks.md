# Tasks: Migración de PHP 7.4 a PHP 8.3

> Etapa 4 del ciclo SDD. Plan partido en tareas pequeñas, ordenadas y verificables. Cada tarea ≈ 1 commit. Se ejecutan una a una (etapa Implement) validando cada una antes de seguir.

- **Plan asociado:** specs/0001-migracion-php-8.3/plan.md
- **Rama de trabajo:** `migracion-php83`
- **Convención:** `PHP83` = `C:\laragon\bin\php\php-8.3.11-Win32-vs16-x64\php.exe`

## Tareas

- [ ] **T0 — Pre-vuelo: rama y baseline**
  - Qué hacer: confirmar árbol git limpio, crear rama `migracion-php83`, correr `composer install` y `phpunit` en PHP 7.4, anotar cuántos tests pasan/fallan.
  - Cómo verificar: existe la rama; queda registrado el baseline (ej. "2/2 tests OK en 7.4").
  - Archivos: ninguno (solo git + medición).

- [ ] **T1 — Constraints de plataforma en composer.json**
  - Qué hacer: añadir `"php": "^8.3"` en `require` y `"config": { "platform": { "php": "8.3.11" } }`.
  - Cómo verificar: `"$PHP83" composer.phar validate` pasa.
  - Archivos: `composer.json`. Depende de: T0.

- [ ] **T2 — Resolver dependencias de riesgo (dry-run → update)**
  - Qué hacer: fijar `illuminate/database` (quitar `*`), subir `firebase/php-jwt`→`^6`, `phpunit`→`^10`/`^11`. Iterar `composer update --dry-run -W` con PHP 8.3 hasta que salga limpio; luego aplicar.
  - Cómo verificar: `composer install` corre sin errores con PHP 8.3 → **cumple CA1**.
  - Archivos: `composer.json`, `composer.lock`. Depende de: T1. Ref: skill `references/dependencies.md`.

- [ ] **T3 — Instalar herramientas de migración (dev)**
  - Qué hacer: `require --dev` de `rector/rector`, `squizlabs/php_codesniffer` + `phpcompatibility/php-compatibility`, `phpstan/phpstan`. Crear `rector.php` y `phpstan.neon` apuntando solo a Controllers/Helpers/models/config.
  - Cómo verificar: `vendor/bin/rector --version`, `vendor/bin/phpcs --version`, `vendor/bin/phpstan --version` responden.
  - Archivos: `composer.json`, `rector.php`, `phpstan.neon`. Depende de: T2. Ref: skill `references/tooling.md`.

- [ ] **T4 — Diagnóstico con PHPCompatibility**
  - Qué hacer: correr phpcs con `testVersion 7.4-8.3`, guardar `migracion-php83-reporte.txt`.
  - Cómo verificar: existe el reporte con el listado de incompatibilidades por archivo/línea.
  - Archivos: genera reporte (no modifica código). Depende de: T3.

- [ ] **T5 — Rector escalón PHP 8.0**
  - Qué hacer: activar solo `UP_TO_PHP_80`, `--dry-run`, revisar diff, aplicar, correr tests con PHP 8.3, commit.
  - Cómo verificar: tests siguen igual o mejor que baseline; commit creado.
  - Archivos: código en Controllers/Helpers/models/config. Depende de: T4.

- [ ] **T6 — Rector escalón PHP 8.1 (null-safety de superglobales)**
  - Qué hacer: activar `UP_TO_PHP_81`, dry-run, aplicar. Aquí Rector convierte gran parte de los ~156 accesos `$_POST/$_GET` a `?? null`. Revisar manualmente los que Rector no cubra (cruzar con T4).
  - Cómo verificar: tests OK; reporte PHPCompatibility ya no marca "null to non-nullable" → avanza hacia CA3.
  - Archivos: ~26 archivos con superglobales. Depende de: T5.

- [ ] **T7 — Rector escalón PHP 8.2**
  - Qué hacer: activar `UP_TO_PHP_82`, dry-run, aplicar, tests, commit.
  - Cómo verificar: tests OK; sin construcciones deprecadas de 8.2 en el reporte.
  - Archivos: código. Depende de: T6.

- [ ] **T8 — Rector escalón PHP 8.3**
  - Qué hacer: activar `UP_TO_PHP_83`, dry-run, aplicar, tests, commit.
  - Cómo verificar: tests OK.
  - Archivos: código. Depende de: T7.

- [ ] **T9 — Manual: actualizar JWT a API 6.x**
  - Qué hacer: en `Helpers/JwtAuth.php` añadir `use Firebase\JWT\Key;` y cambiar las 2 llamadas `JWT::decode($jwt, $this->key, ['HS256'])` por `JWT::decode($jwt, new Key($this->key, 'HS256'))` (líneas ~83 y ~130). Ajustar el catch a las excepciones de 6.x si aplica.
  - Cómo verificar: probar login (`signup`) y validación (`checktoken`); ambos funcionan.
  - Archivos: `Helpers/JwtAuth.php`. Depende de: T2.

- [ ] **T10 — Manual: propiedades dinámicas (16 clases)**
  - Qué hacer: declarar las propiedades en las 16 clases detectadas (preferido), p. ej. `private string $fechaactual;`. Para clases con propiedades realmente dinámicas, usar `#[\AllowDynamicProperties]` como excepción justificada.
  - Cómo verificar: reporte PHPCompatibility/PHPStan sin "dynamic property" → **cumple CA4**.
  - Archivos: 16 clases (Controllers en su mayoría + `JwtAuth`, `ConsultaGlobal`). Depende de: T8.

- [ ] **T11 — Verificación estática (PHPStan)**
  - Qué hacer: `phpstan analyse --level=5`; corregir errores nuevos vs estado inicial.
  - Cómo verificar: sin errores nuevos → **cumple CA5**.
  - Archivos: los que PHPStan marque. Depende de: T10.

- [ ] **T12 — Verificación funcional (tests + humo)**
  - Qué hacer: suite completa de PHPUnit en 8.3 comparada con baseline (T0); prueba de humo de login, generación PDF/CPE y consultas MySQL revisando logs.
  - Cómo verificar: tests ≥ baseline (**CA2**); sin `Deprecated`/`Fatal` en flujos críticos (**CA3**).
  - Archivos: ninguno (validación). Depende de: T11.

- [ ] **T13 — Cambiar PHP por defecto a 8.3 en Laragon**
  - Qué hacer: solo si T12 está verde, fijar PHP 8.3 como versión default del entorno.
  - Cómo verificar: el proyecto levanta en 8.3 sin errores.
  - Depende de: T12.

## Verificación final (etapa Verify)
- [ ] CA1 — `composer install` corre en PHP 8.3 (T2)
- [ ] CA2 — Tests ≥ baseline (T12)
- [ ] CA3 — Flujos críticos sin Deprecated/Fatal (T12)
- [ ] CA4 — Sin propiedades dinámicas sin declarar (T10)
- [ ] CA5 — PHPStan nivel 5 sin errores nuevos (T11)
- [ ] CA6 — Dependencias fijas y compatibles, sin `*` (T2)
- [ ] Revisado contra la constitución (seguridad, datos personales)
