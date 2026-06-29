# Spec: Control de venta de silicona por onza

> Feature end-to-end (backend + una sección en el front Angular). Enfoque elegido: cada sabor es un producto vendido por onza (sin cambios de esquema de BD).

- **ID:** 0002
- **Estado:** Aprobada (2026-06-25)
- **Autor:** rdurand
- **Fecha:** 2026-06-25

## Problema / Motivación
Se vende silicona aromatizante **por onza**, en varios sabores (fresa, manzana, uva, chicle, cereza). Hoy no hay forma de registrar cuántas onzas se venden ni de saber el stock por sabor. El dueño quiere que la vendedora (Xiomara) registre en el sistema las onzas vendidas de cada sabor en cada venta, para tener **control de ventas e inventario por onza**.

## Objetivo
Permitir registrar y controlar ventas de silicona por onza y por sabor, reutilizando el flujo de ventas/caja/stock que ya existe, con una sección de menú dedicada para que la venta sea rápida.

## Alcance
**Incluye:**
- Crear la unidad de medida **ONZA**.
- Crear cada sabor como un **producto** con unidad ONZA, su stock (en onzas) y precio por onza.
- Una **sección de menú "Silicona"** en el front (sidebar + ruta + página) para vender y consultar.
- Registrar la venta indicando **sabor + cantidad de onzas** (el flujo de venta actual ya soporta cantidad decimal).
- Un **reporte/consulta** de onzas vendidas y stock por sabor en un periodo.

**NO incluye (fuera de alcance):**
- Venta por litro o galón (descartado por ahora).
- Precios escalonados/por combo (el precio es lineal).
- Modelar sabores como atributos/variantes (enfoque B) ni un módulo con tablas nuevas (enfoque C).
- Cambios al esquema de la base de datos.

## Criterios de aceptación
- [ ] CA1: Existe la unidad de medida "ONZA" en el catálogo.
- [ ] CA2: Existen los productos de sabor (fresa, manzana, uva, chicle, cereza) con unidad ONZA, stock y precio por onza.
- [ ] CA3: Se puede registrar una venta eligiendo sabor + cantidad de onzas (números **enteros**); el total se calcula **lineal**: `0.50 × onzas` (precio con 2 decimales).
- [ ] CA4: La venta descuenta el stock del sabor vendido y queda registrada en caja como cualquier otra venta (con su comprobante).
- [ ] CA5: Existe una sección de menú "Silicona" (con permiso, ruta y página) para registrar la venta y consultar.
- [ ] CA6: Hay una consulta/reporte que muestra onzas vendidas y stock restante por sabor en un rango de fechas.

## Reglas de negocio / restricciones
- **Precio:** S/0.50 por onza, almacenado con **2 decimales** (`decimal(_,2)`). Lineal: 2 oz = S/1.00, 3 oz = S/1.50, etc.
- **Cantidad en onzas ENTERAS** (sin decimales): se vende 1, 2, 3… onzas, nunca 1.5.
- **Sabores iniciales:** fresa, manzana, uva, chicle, cereza.
- **Solo se vende por onza** (no litro/galón).
- La cantidad de onzas por venta la ingresa la vendedora en el momento.
- Reutiliza el sistema de caja y comprobantes existente; no se inventa un flujo de pago nuevo.
- **Stack actual (confirmado):** Front Node 14, Backend PHP 7.4.23. La migración a versiones nuevas es otra spec aparte (0001).

## Decisiones confirmadas (2026-06-25)
1. **Precio:** S/0.50 por onza, con 2 decimales. ✅
2. **Sabores:** fresa, manzana, uva, chicle, cereza. ✅
3. **Onzas enteras** (sin decimales). ✅

## Preguntas abiertas menores (se afinan en el plan, no bloquean)
- **Stock:** se asume control de stock en onzas enteras por sabor. Equivalencia para reposición: **1 botella ≈ 8 oz, 1 litro ≈ 4 botellas (≈32 oz), 1 bidón 3.5 L ≈ 112 oz**. No bloquea la venta.
- **Permisos:** se asume un permiso nuevo "SILICONA" en el sistema de módulos existente (la vendedora vende; el admin ve reportes).

## Ampliación futura (fuera de alcance ahora — faltan datos)
- **Venta por botella con precio fijo:** la vendedora podrá seleccionar "Botella Chica / Mediana / Grande" y el precio sale automático (ej. S/4.00). Se modela igual que la onza: cada presentación = un producto con su precio. Pendiente: precios y capacidad (oz) de cada tamaño. Se hará cuando el dueño tenga los datos reales.
