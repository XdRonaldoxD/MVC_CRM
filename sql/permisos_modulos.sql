-- ============================================================================
--  PERMISOS POR PERFIL  (módulos visibles según perfil)
--  Aplicar en producción una sola vez. Es IDEMPOTENTE: se puede correr varias
--  veces sin duplicar datos.
--
--  Cómo aplicar:
--   - phpMyAdmin / Adminer: pestaña SQL -> pegar y ejecutar.
--   - CLI:  mysql -u USUARIO -p NOMBRE_BD < sql/permisos_modulos.sql
--   - Laragon (local): ya está aplicado; este archivo es para el servidor.
-- ============================================================================

-- 1) Tabla pivote: qué módulos tiene asignado cada perfil.
CREATE TABLE IF NOT EXISTS `perfil_modulo` (
  `id_perfil_modulo` INT NOT NULL AUTO_INCREMENT,
  `id_perfil` INT NOT NULL,
  `id_modulo` INT NOT NULL,
  `vigente_perfil_modulo` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id_perfil_modulo`),
  UNIQUE KEY `uq_perfil_modulo` (`id_perfil`, `id_modulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2) Catálogo de módulos del panel (reusa la tabla `modulo` ya existente).
--    clave (link_modulo) = se usa en el front (puedeVer) y en el PermisoGate.
--    grupo (clase_modulo) = agrupación en la pantalla de Permisos.
--    Inserta solo si la clave aún no existe (idempotente).
INSERT INTO `modulo` (`glosa_modulo`, `link_modulo`, `clase_modulo`, `orden_modulo`, `vigente_modulo`)
SELECT cat.g, cat.l, cat.c, cat.o, cat.v FROM (
  SELECT 'Sucursal'   AS g, 'SUCURSAL'               AS l, 'INVENTARIOS'     AS c, 1  AS o, 1 AS v UNION ALL
  SELECT 'Bodega',          'BODEGAS',                   'INVENTARIOS',          2,     1 UNION ALL
  SELECT 'Marca',           'MARCAS',                    'INVENTARIOS',          3,     1 UNION ALL
  SELECT 'Categoria',       'CATEGORIAS',                'INVENTARIOS',          4,     1 UNION ALL
  SELECT 'Producto',        'PRODUCTOS',                 'INVENTARIOS',          5,     1 UNION ALL
  SELECT 'Atributo',        'ATRIBUTOS',                 'INVENTARIOS',          6,     1 UNION ALL
  SELECT 'Pago Nota Venta', 'PAGO NOTA VENTA',           'PAGOS',                7,     1 UNION ALL
  SELECT 'Ventas',          'VENTAS',                    'PAGOS',                8,     1 UNION ALL
  SELECT 'Caja',            'CAJA',                      'PAGOS',                9,     1 UNION ALL
  SELECT 'Anular Doc.',     'ANULAR DOCUMENTOS',         'PAGOS',                10,    1 UNION ALL
  SELECT 'Ver todas cajas', 'VER TODAS LAS CAJAS',       'PAGOS',                19,    1 UNION ALL
  SELECT 'Reporte Prod.',   'REPORTE PRODUCTOS',         'REPORTE',              11,    1 UNION ALL
  SELECT 'Rep. Venta Prod.','REPORTE VENTA PRODUCTO',    'REPORTE',              12,    1 UNION ALL
  SELECT 'Libro Ventas',    'LIBRO VENTAS',              'REPORTE',              13,    1 UNION ALL
  SELECT 'Kardex',          'KARDEX',                    'REPORTE',              14,    1 UNION ALL
  SELECT 'Pedidos',         'PEDIDOS',                   'TIENDA EN LINEA',      15,    1 UNION ALL
  SELECT 'Slider',          'SLIDER',                    'TIENDA EN LINEA',      15,    1 UNION ALL
  SELECT 'Chat Cliente',    'CHAT CLIENTE',              'TIENDA EN LINEA',      16,    1 UNION ALL
  SELECT 'Promociones',     'PROMOCIONES',               'TIENDA EN LINEA',      17,    1
) AS cat
WHERE NOT EXISTS (
  SELECT 1 FROM `modulo` m WHERE m.`link_modulo` = cat.l
);

-- 2b) El nombre mostrado (glosa_modulo) debe ser igual al del mantenedor (sidebar).
UPDATE `modulo` SET `glosa_modulo` = `link_modulo` WHERE `vigente_modulo` = 1;

-- 3) (OPCIONAL) Default: asigna TODOS los módulos al perfil FARMACEUTICO (id_perfil=5),
--    para que no quede el panel en blanco. El ADMINISTRADOR (id_perfil=1) NO se asigna:
--    tiene acceso total por código. Ajusta/elimina según tus perfiles reales.
INSERT IGNORE INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vigente_perfil_modulo`)
SELECT 5, m.`id_modulo`, 1 FROM `modulo` m WHERE m.`vigente_modulo` = 1;
