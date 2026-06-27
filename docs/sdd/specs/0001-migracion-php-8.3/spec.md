# Spec: Migración de PHP 7.4 a PHP 8.3

> Spec sembrada como ejemplo del ciclo SDD. Conecta con la skill `php-upgrade-74-83`, que será la base del plan.

- **ID:** 0001
- **Estado:** Borrador
- **Autor:** rdurand
- **Fecha:** 2026-06-25

## Problema / Motivación
El CRM corre sobre PHP 7.4, que está fuera de soporte de seguridad. Quedarse atrás acumula deuda técnica, bloquea librerías modernas y deja vulnerabilidades sin parchar. PHP 8.3 trae soporte vigente, mejor rendimiento y tipado más seguro.

## Objetivo
Llevar el backend de PHP 7.4 a PHP 8.3 sin perder funcionalidad ni cobertura de pruebas, de forma incremental y reversible.

## Alcance
**Incluye:**
- Actualizar dependencias de `composer.json` para que resuelvan en PHP 8.3.
- Refactorizar el código de `Controllers/`, `Helpers/`, `models/`, `config/` a sintaxis compatible con 8.3.
- Resolver breaking changes 8.0 → 8.3 (propiedades dinámicas, null a no-nullable, etc.).

**NO incluye (fuera de alcance):**
- Reescribir la arquitectura MVC ni cambiar de framework.
- Migrar el frontend Angular (`administrador_mvc`).
- Nuevas features funcionales.

## Criterios de aceptación
- [ ] CA1: `composer install` corre sin errores usando el binario PHP 8.3.
- [ ] CA2: La suite de PHPUnit pasa **igual o mejor** que el baseline capturado en PHP 7.4 (ningún test que pasaba ahora falla).
- [ ] CA3: Los flujos críticos (login, generación de PDF/CPE, consultas a MySQL) funcionan sin `Deprecated`/`Fatal` en el log bajo PHP 8.3.
- [ ] CA4: No quedan propiedades dinámicas sin declarar ni `#[\AllowDynamicProperties]` justificado.
- [ ] CA5: PHPStan (nivel 5) no reporta errores nuevos respecto al estado inicial.
- [ ] CA6: `firebase/php-jwt`, `illuminate/database` y `phpunit` quedan en versiones compatibles y con rango fijo (sin comodín `*`).

## Reglas de negocio / restricciones
- Trabajar en rama `migracion-php83`, un commit por escalón/tarea.
- No tocar `vendor/` a mano.
- No subir datos reales al repo durante las pruebas.

## Preguntas abiertas
- ¿`firebase/php-jwt` salta directo a 6.x (requiere adaptar el código de auth) o puente por 5.x? Decidir en el plan.
- ¿`cyber-duck/laravel-excel` sigue en uso o se puede retirar si bloquea a Laravel 10/11?
