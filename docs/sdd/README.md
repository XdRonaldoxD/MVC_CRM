# SDD — Spec-Driven Development (CRM)

Método para construir features con IA de forma **predecible y verificable**: primero se escribe *qué* se quiere y *cómo se valida*, y recién entonces se construye. Evita que el agente genere código plausible pero equivocado.

## El ciclo

```
Constitución (1 vez)
      │
      ▼
  Specify ─► Plan ─► Tasks ─► Implement ─► Verify ─┐
      ▲                                            │
      └──────────────── (si falla, ajusta) ◄───────┘
```

| Etapa | Pregunta | Artefacto |
|-------|----------|-----------|
| **Constitución** | ¿Cuáles son las reglas inamovibles del proyecto? | `constitution.md` (se escribe una vez) |
| **Specify** | ¿QUÉ construyo y cómo sé que quedó bien? | `specs/NNNN-nombre/spec.md` |
| **Plan** | ¿CÓMO lo construyo (técnico)? | `specs/NNNN-nombre/plan.md` |
| **Tasks** | ¿En qué pasos pequeños se divide? | `specs/NNNN-nombre/tasks.md` |
| **Implement** | Ejecutar las tareas una a una | commits por tarea |
| **Verify** | ¿Cumple los criterios de aceptación? | tests + checklist |

## Cómo trabajarlo con Claude

1. **Nueva feature** → copia `templates/spec.md` a `specs/NNNN-nombre/spec.md` y pídele a Claude: *"llena la spec de [feature] según la constitución"*.
2. Revisa y aprueba la spec. **No avances sin criterios de aceptación claros.**
3. *"genera el plan"* → `plan.md`. Revisa.
4. *"genera las tareas"* → `tasks.md`. Deben ser pequeñas y verificables.
5. *"implementa la tarea T1"* → Claude ejecuta y commitea. Una a una.
6. *"verifica contra los criterios de aceptación"* → si falla, vuelve al plan.

> Regla de oro: el humano aprueba **spec** y **plan** antes de tocar código. Ahí es donde se evita el 90% de los errores.

## Numeración

Las specs van numeradas: `0001-`, `0002-`, … en orden de creación. La carpeta `0001-migracion-php-8.3/` ya está sembrada como ejemplo y conecta con la skill `php-upgrade-74-83`.
