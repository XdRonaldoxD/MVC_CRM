# Constitución del proyecto — CRM MVC

Reglas inamovibles que rigen TODA feature. Cualquier spec, plan o implementación que las contradiga debe rechazarse o escalar para cambiar la constitución primero. Se escribe una vez y se modifica rara vez (con commit explícito y justificación).

## 1. Stack y estructura

- **Backend:** PHP MVC (Controllers/, Helpers/, models/, config/) + MySQL.
- **Gestor de dependencias:** Composer. Nunca se edita `vendor/` a mano; las dependencias se cambian vía `composer.json`.
- **Entorno local:** Laragon (Windows). PHP 8.3 disponible en `C:\laragon\bin\php\php-8.3.11-Win32-vs16-x64\php.exe`.
- **Frontend separado:** `administrador_mvc` (Angular). No mezclar responsabilidades entre repos.

## 2. Calidad y verificación

- **Tests primero como red de seguridad.** Ninguna feature se da por terminada sin que la suite de PHPUnit quede igual o mejor que antes.
- **Un commit por tarea verificable.** Facilita revertir y ubicar regresiones (`git bisect`).
- **Rama por feature.** Nunca trabajar directo sobre `main`.
- **Cambios reversibles.** Antes de algo destructivo (migraciones de BD, borrados), debe existir respaldo y plan de rollback.

## 3. Seguridad (innegociable)

- **Queries parametrizadas siempre.** Prohibido concatenar input de usuario en SQL (previene inyección).
- **Validación de entrada** en todo dato que venga de formularios, querystring o APIs externas.
- **Autenticación:** tokens/JWT con librerías mantenidas y vigentes; nunca credenciales hardcodeadas en código ni en archivos versionados.
- Referencia de criterios: OWASP Top 10.

## 4. Datos personales (cumplimiento)

- Información de estudiantes, docentes y colaboradores es **confidencial** (Ley 29733 - Perú; normativa SUNEDU/MINEDU).
- No exponer datos personales identificables fuera del contexto autorizado.
- No subir datos reales de producción al repositorio ni a entornos de prueba sin anonimizar.

## 5. Proceso SDD

- Toda feature pasa por el ciclo: **Spec → Plan → Tasks → Implement → Verify**.
- El humano **aprueba la spec y el plan** antes de escribir código.
- Cada spec define **criterios de aceptación objetivos y verificables**; sin ellos no se planifica.
- Cuando exista una skill que cubra el dominio (ej. `php-upgrade-74-83`), el plan se apoya en ella.

## 6. Estilo

- Código nuevo imita el estilo del código circundante (naming, comentarios, idioma).
- Documentación y comentarios en español.
