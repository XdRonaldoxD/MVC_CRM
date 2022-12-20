<?php
require_once "config/database_mysql.php";

class ConsultaGlobal
{
    public $db;
    public function __construct()
    {
        $this->db = database::conectar();
    }

    public function ListarProductoApi($condicion)
    {
        $sql = "SELECT producto.*,
        (SELECT GROUP_CONCAT(id_producto SEPARATOR '~') from producto_relacionado where idproductopadre_producto_relacionado=producto.id_producto) as producto_relacionado,
        (SELECT GROUP_CONCAT(categoria.id_categoria,'@',categoria.glosa_categoria SEPARATOR '~') 
        from categoria_producto
        inner join categoria on categoria.id_categoria = categoria_producto.id_categoria
        where id_producto=producto.id_producto) 
        as categorias,
        (SELECT GROUP_CONCAT(producto_imagen.path_producto_imagen SEPARATOR '~') 
        from producto_imagen where id_producto=producto.id_producto
        ) as producto_imagen,
		(select GROUP_CONCAT(nombre_producto_color,',',hexadecimal_producto_color,',',id_producto_color SEPARATOR '~')
		FROM producto_color where id_producto=producto.id_producto
		) as color_producto,
		  (SELECT GROUP_CONCAT(glosa_especificaciones_producto,',',respuesta_especificaciones_producto SEPARATOR '~') 
        from especificaciones_producto where id_producto=producto.id_producto
        ) as especificacion_producto,
        (SELECT GROUP_CONCAT(atributo.glosa_atributo,',',atributo_producto.id_atributo_producto SEPARATOR '~') 
        from atributo_producto
        inner join atributo using (id_atributo)
        where id_producto=producto.id_producto
        ) as atributo_producto
        from producto
        $condicion ";
        $marcar = $this->db->prepare($sql);
        $marcar->execute();
        $result = $marcar->fetch(PDO::FETCH_OBJ);
        $marcar = null;
        return $result;
    }

    public function ListarCategoriaProductoApi($condicion)
    {
        $sql = "SELECT categoria.glosa_categoria,categoria.urlamigable_categoria,producto.*,null as producto_relacionado,null as categorias,
        (SELECT GROUP_CONCAT(producto_imagen.path_producto_imagen SEPARATOR '~') 
        from producto_imagen where id_producto=producto.id_producto
        ) as producto_imagen,
		(select GROUP_CONCAT(nombre_producto_color,',',hexadecimal_producto_color,',',id_producto_color SEPARATOR '~')
		FROM producto_color where id_producto=producto.id_producto
		) as color_producto,
		  (SELECT GROUP_CONCAT(glosa_especificaciones_producto,',',respuesta_especificaciones_producto SEPARATOR '~') 
        from especificaciones_producto where id_producto=producto.id_producto
        ) as especificacion_producto,
        (SELECT GROUP_CONCAT(atributo.glosa_atributo,',',atributo_producto.id_atributo_producto SEPARATOR '~') 
        from atributo_producto
        inner join atributo using (id_atributo)
        where id_producto=producto.id_producto
        ) as atributo_producto
        from categoria_producto INNER JOIN  producto USING (id_producto)
        INNER JOIN categoria USING (id_categoria)
        $condicion ";
        $CategoriaProducto = $this->db->prepare($sql);
        $CategoriaProducto->execute();
        $result = $CategoriaProducto->fetchAll(PDO::FETCH_OBJ);
        $CategoriaProducto = null;
        return $result;
    }

    public function EstructuraFilterApi($condicion)
    {
        $sql = "SELECT max(producto.precioventa_producto) as precio_mayor,min(producto.precioventa_producto) as precio_menor from categoria_producto INNER JOIN  producto USING (id_producto)
        $condicion ";
        $CategoriaProducto = $this->db->prepare($sql);
        $CategoriaProducto->execute();
        $result = $CategoriaProducto->fetch(PDO::FETCH_OBJ);
        $CategoriaProducto = null;
        return $result;
    }

    public function ListarPedidoApi($condicion)
    {
        $sql = "SELECT  
        (SELECT SUM(pedido_detalle.cantidad_pedido_detalle)
        FROM pedido_detalle
        where id_pedido=pedido.id_pedido
        ) as cantidad_productos,pedido.id_pedido,pedido.numero_pedido,
        estado_pedido.glosa_estado_pedido,pedido.fechacreacion_pedido,pedido.valortotal_pedido
        FROM `pedido` 
        INNER JOIN estado_preparacion USING (id_estado_preparacion)
        INNER JOIN estado_pago USING (id_estado_pago)
        INNER JOIN estado_pedido USING (id_estado_pedido)
        where pedido.id_cliente=$condicion
        and vigente_pedido=1";
        $marcar = $this->db->prepare($sql);
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_OBJ);
        $marcar = null;
        return $result;
    }


    public  function TraerDatosProductos($condicion)
    {
        $consulta = "SELECT producto.*,
        (select GROUP_CONCAT(id_producto SEPARATOR '~') from producto_relacionado where idproductopadre_producto_relacionado=producto.id_producto) as producto_relacionado,
        (select GROUP_CONCAT(CONCAT(hexadecimal_producto_color,'|',id_producto_color,'|',nombre_producto_color) SEPARATOR '~') from producto_color where id_producto=producto.id_producto) as color_producto,
        (select GROUP_CONCAT(CONCAT(id_producto_imagen,'|',nombre_producto_imagen,'|',path_producto_imagen,'|',portada_producto_imagen) SEPARATOR '~') from producto_imagen where id_producto=producto.id_producto ORDER BY orden_producto_imagen ) as producto_imagen,
        (select GROUP_CONCAT(CONCAT(id_especificaciones_producto,'|',glosa_especificaciones_producto,'|',respuesta_especificaciones_producto) SEPARATOR '~') from especificaciones_producto where id_producto=producto.id_producto) as producto_especificaciones,
        (select GROUP_CONCAT(CONCAT(id_categoria_producto,'|',id_categoria) SEPARATOR '~') from categoria_producto where id_producto=producto.id_producto) as categoria_producto,
        (select GROUP_CONCAT(CONCAT(atributo_producto.id_atributo_producto,'|',atributo_producto.id_atributo,'|',atributo.glosa_atributo,'|',atributo_producto.stock_atributo) SEPARATOR '~') from atributo_producto
        inner join atributo on  atributo_producto.id_atributo=atributo.id_atributo
         where id_producto=producto.id_producto) as atributo_producto
        FROM producto
        $condicion
        ";
        $producto = $this->db->prepare($consulta);
        $producto->execute();
        $result = $producto->fetch(PDO::FETCH_OBJ);
        $producto = null;
        return  $result;
    }

    public  function ConsultaProductosRelacionado($condicion)
    {
        $consulta = "SELECT *,nombre_producto_imagen as glosa_producto
        FROM producto_imagen 
        $condicion
        ";
        $producto = $this->db->prepare($consulta);
        $producto->execute();
        $result = $producto->fetch(PDO::FETCH_OBJ);
        $producto = null;
        return  $result;
    }

    public function ConsultaPedidoDetalle($condicion)
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/";
        $consulta = "SELECT pedido_detalle.*,producto.glosa_producto,producto.codigo_producto,producto.precioventa_producto,
        (SELECT concat('$imagens',path_producto_imagen) from producto_imagen where portada_producto_imagen=1 
        and id_producto=producto.id_producto
        ) as imagen_producto,
        (SELECT GROUP_CONCAT(glosa_atributo,',',cantidad_pedido_detalle_atributo_producto,',',nombre_color_detalle_atributo_producto SEPARATOR '~') 
        from pedido_detalle_atributo_producto 
        join atributo using (id_atributo)
        where id_pedido_detalle=pedido_detalle.id_pedido_detalle
        ) as pedido_detalle_atributo_producto
         FROM `pedido_detalle` INNER JOIN producto USING (id_producto)
        $condicion";
        $producto = $this->db->prepare($consulta);
        $producto->execute();
        $result = $producto->fetchAll(PDO::FETCH_OBJ);
        $producto = null;
        return  $result;
    }

    public function ListarPedido($DatosPost, $limit = false)
    {
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $consulta_cliente = ' ';
        $buscar = $DatosPost->search->value;

        if (isset($DatosPost->glosa_cliente)) {
            $consulta_cliente = " and CONCAT(cliente.nombre_cliente,' ',cliente.apellidopaterno_cliente,' ',cliente.apellidomaterno_cliente) LIKE %% ";
        }
        if (!empty($buscar)) {
            $consulta_cliente = " and (CONCAT(cliente.nombre_cliente,' ',cliente.apellidopaterno_cliente,' ',cliente.apellidomaterno_cliente) LIKE '%$buscar%' or estado_pago.glosa_estado_pago LIKE '%$buscar%' or estado_pedido.glosa_estado_pedido LIKE '%$buscar%' 
            or estado_preparacion.glosa_estado_preparacion LIKE '%$buscar%')
            or pedido.numero_pedido = '$buscar'
            ";
        }
        if (!empty($DatosPost->id_estado_pago)) {
            $id_estado_pago = implode(',', $DatosPost->id_estado_pago);
            $id_estado_pago = "($id_estado_pago)";
            $consulta_cliente .= " and pedido.id_estado_pago in $id_estado_pago ";
        }
        if (!empty($DatosPost->id_estado_pedido)) {
            $id_estado_pedido = implode(',', $DatosPost->id_estado_pedido);
            $id_estado_pedido = "($id_estado_pedido)";
            $consulta_cliente .= " and pedido.id_estado_pedido in $id_estado_pedido  ";
        }
        if (!empty($DatosPost->id_estado_preparacion)) {
            $id_estado_preparacion = implode(',', $DatosPost->id_estado_preparacion);
            $id_estado_preparacion = "($id_estado_preparacion)";
            $consulta_cliente .= " and pedido.id_estado_preparacion in $id_estado_preparacion ";
        }
        $consulta_limite = '';
        if ($limit) {
            $consulta_limite = " ORDER BY pedido.id_pedido desc
            LIMIT {$longitud} OFFSET $DatosPost->start ";
        }
        $sql = "SELECT  
                estado_preparacion.glosa_estado_preparacion,
                estado_pedido.glosa_estado_pedido,
                estado_pago.glosa_estado_pago,
                cliente.nombre_cliente,
                cliente.apellidopaterno_cliente,
                cliente.apellidomaterno_cliente,
				pedido.*,
				(select COUNT(*) from pedido_detalle where id_pedido=pedido.id_pedido) as cantidad,
				IF(pedido.retiroentienda_pedido=1,'Despacho normal a domicilio','Retiro en Tienda') as retiroentienda_pedido
               FROM pedido
               INNER JOIN cliente USING (id_cliente)
               INNER JOIN estado_preparacion USING (id_estado_preparacion)
               INNER JOIN estado_pago USING (id_estado_pago)
               INNER JOIN estado_pedido USING (id_estado_pedido)
               WHERE pedido.vigente_pedido=1
               $consulta_cliente
               $consulta_limite
              
               ";
        $marcar = $this->db->prepare($sql);
        $marcar->execute();
        $result = $marcar->fetchAll(PDO::FETCH_OBJ);
        $marcar = null;
        return $result;
    }

    public function TraerChatLineaActivo($fecha_desde, $fecha_hasta, $linea)
    {
        $condicion_extra = '';
        if ($linea === 'true') {
            $condicion_extra = " and estado_linea_log_chat=1 ";
        }
        $consulta = "SELECT *,0 as chat_seleccionado FROM `log_chat` where fechacreacion_log_chat >= '$fecha_desde'
        and fechacreacion_log_chat <= '$fecha_hasta'
        and estado_log_chat=1
        $condicion_extra";
        $producto = $this->db->prepare($consulta);
        $producto->execute();
        $result = $producto->fetchAll(PDO::FETCH_OBJ);
        $producto = null;
        return  $result;
    }

    public function LiberarGroupConcat()
    {
        $consulta = "SET group_concat_max_len = 1000000";
        $producto = $this->db->prepare($consulta);
        $producto->execute();
        $producto = null;
    }

    public function ConsultaSingular($sql)
    {
        $ConsultaSingular = $this->db->prepare($sql);
        $ConsultaSingular->execute();
        $result = $ConsultaSingular->fetch(PDO::FETCH_OBJ);
        $ConsultaSingular = null;
        return $result;
    }
    public function ConsultaGlobal($sql)
    {
        $ConsultaGlobal = $this->db->prepare($sql);
        $ConsultaGlobal->execute();
        $result = $ConsultaGlobal->fetchAll(PDO::FETCH_OBJ);
        $ConsultaGlobal = null;
        return $result;
    }
}
