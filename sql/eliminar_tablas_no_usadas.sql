-- ============================================================================
-- ELIMINAR TABLAS NO USADAS (BD local farxcfyq_boticarosa)
-- Generado tras analizar referencias en MVC_CRM y en TODOS los proyectos de www.
-- Estas 69 tablas NO se referencian en ningún código (son leftovers de un ERP
-- veterinario/clínico). BACKUP COMPLETO en: sql/backup/farxcfyq_boticarosa_backup.sql
-- Revisar antes de ejecutar. IRREVERSIBLE (salvo restaurar del backup).
-- Ejecutar en phpMyAdmin (BD farxcfyq_boticarosa -> SQL) o:
--   mysql -u root farxcfyq_boticarosa < sql/eliminar_tablas_no_usadas.sql
-- ============================================================================
SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `aplicaciones_producto`;
DROP TABLE IF EXISTS `bloqueo_general`;
DROP TABLE IF EXISTS `categorias_destacada`;
DROP TABLE IF EXISTS `cliente_abono`;
DROP TABLE IF EXISTS `comprobante_interno`;
DROP TABLE IF EXISTS `convulsion`;
DROP TABLE IF EXISTS `despacho_detalle`;
DROP TABLE IF EXISTS `detalle_cirugia`;
DROP TABLE IF EXISTS `detalle_cliente_abono`;
DROP TABLE IF EXISTS `detalle_convenio`;
DROP TABLE IF EXISTS `detalle_credito_cliente`;
DROP TABLE IF EXISTS `detalle_lista_precio`;
DROP TABLE IF EXISTS `detalle_listado_servicio`;
DROP TABLE IF EXISTS `detalle_matriz_atributo`;
DROP TABLE IF EXISTS `detalle_orden_compra`;
DROP TABLE IF EXISTS `detalle_presupuesto`;
DROP TABLE IF EXISTS `detalle_presupuesto_asignado`;
DROP TABLE IF EXISTS `detalle_presupuesto_asignado_staff`;
DROP TABLE IF EXISTS `detalle_presupuesto_global`;
DROP TABLE IF EXISTS `detalle_traspaso`;
DROP TABLE IF EXISTS `detalle_turno`;
DROP TABLE IF EXISTS `devolucion_ingreso`;
DROP TABLE IF EXISTS `diagnosticos`;
DROP TABLE IF EXISTS `estado_atencion`;
DROP TABLE IF EXISTS `estado_orden_compra`;
DROP TABLE IF EXISTS `estado_paciente`;
DROP TABLE IF EXISTS `estado_paciente_cirugia`;
DROP TABLE IF EXISTS `estado_recordatorio_envio`;
DROP TABLE IF EXISTS `estado_reserva`;
DROP TABLE IF EXISTS `examensangre`;
DROP TABLE IF EXISTS `guia_despacho_detalle`;
DROP TABLE IF EXISTS `historial_lista_precio`;
DROP TABLE IF EXISTS `intervalos`;
DROP TABLE IF EXISTS `listado_servicio`;
DROP TABLE IF EXISTS `log_costo_servicio`;
DROP TABLE IF EXISTS `log_pagos`;
DROP TABLE IF EXISTS `log_soporte_cliente`;
DROP TABLE IF EXISTS `matriz_atributo`;
DROP TABLE IF EXISTS `modulo_atencion`;
DROP TABLE IF EXISTS `motivo_cancelacion`;
DROP TABLE IF EXISTS `neurolocalizacion`;
DROP TABLE IF EXISTS `nota_credito_detalle`;
DROP TABLE IF EXISTS `parametro_cirugia`;
DROP TABLE IF EXISTS `plantilla_documento`;
DROP TABLE IF EXISTS `prevision`;
DROP TABLE IF EXISTS `producto_cirugia`;
DROP TABLE IF EXISTS `producto_proveedor`;
DROP TABLE IF EXISTS `producto_transportista`;
DROP TABLE IF EXISTS `sedacion`;
DROP TABLE IF EXISTS `servicio_sucursal`;
DROP TABLE IF EXISTS `staff_servicio`;
DROP TABLE IF EXISTS `sub_categorias_destacada`;
DROP TABLE IF EXISTS `sub_categorias_destacada_producto`;
DROP TABLE IF EXISTS `tipo_abono_cliente`;
DROP TABLE IF EXISTS `tipo_atencion`;
DROP TABLE IF EXISTS `tipo_cobro`;
DROP TABLE IF EXISTS `tipo_dato_extra`;
DROP TABLE IF EXISTS `tipo_despacho_transportista`;
DROP TABLE IF EXISTS `tipo_detalle_presupuesto_global`;
DROP TABLE IF EXISTS `tipo_inventario_bodega`;
DROP TABLE IF EXISTS `tipo_inventario_tipo_producto`;
DROP TABLE IF EXISTS `tipo_log`;
DROP TABLE IF EXISTS `tipo_reserva`;
DROP TABLE IF EXISTS `tipo_servicio`;
DROP TABLE IF EXISTS `tipo_urgencia`;
DROP TABLE IF EXISTS `tipo_vinculo`;
DROP TABLE IF EXISTS `turno_sucursal`;
DROP TABLE IF EXISTS `ubicacion_producto_bodega`;
DROP TABLE IF EXISTS `zona_comuna`;
SET FOREIGN_KEY_CHECKS=1;
