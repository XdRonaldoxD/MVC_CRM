<?php
require_once "models/ConsultaGlobal.php";
require_once "models/Ingreso.php";
require_once "models/Negocio.php";
require_once "models/Boleta.php";
require_once "models/Factura.php";
require_once "models/NotaVenta.php";

class VentaController
{

    public function ListarVentas()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        if ($DatosPost->length < 1) {
            $longitud = 10;
        } else {
            $longitud = $DatosPost->length;
        }
        $buscar = $DatosPost->search->value;
        $consulta = "SELECT negocio.id_negocio as id_negocio_global,negocio.*,cliente.*,boleta.*,factura.*,nota_venta.*,'" . RUTA_ARCHIVO . "/archivo' as ruta_archivo,
        s_boleta.nombre_staff as nombre_staff_boleta,s_boleta.apellidopaterno_staff as apellidopaterno_staff_boleta,s_boleta.apellidomaterno_staff as apellidomaterno_staff_boleta,
        s_factura.nombre_staff as nombre_staff_factura,s_factura.apellidopaterno_staff as apellidopaterno_staff_factura,s_factura.apellidomaterno_staff as apellidomaterno_staff_factura,
        s_nota_venta.nombre_staff as nombre_staff_nota_venta,s_nota_venta.apellidopaterno_staff as apellidopaterno_staff_nota_venta,s_nota_venta.apellidomaterno_staff as apellidomaterno_staff_nota_venta
        FROM negocio
        inner join cliente on negocio.id_cliente=negocio.id_cliente
        LEFT JOIN boleta on boleta.id_negocio=negocio.id_negocio
        LEFT JOIN factura on factura.id_negocio=negocio.id_negocio
        LEFT JOIN nota_venta on nota_venta.id_negocio=negocio.id_negocio
    
        left join usuario as u_boleta on u_boleta.id_usuario=boleta.id_usuario
        left join staff as s_boleta on s_boleta.id_staff=u_boleta.id_staff
 
        left join usuario as u_factura on u_factura.id_usuario=factura.id_usuario
        left join staff as s_factura on s_factura.id_staff=u_factura.id_staff
    
        left join usuario as u_nota_venta on u_nota_venta.id_usuario=nota_venta.id_usuario
        left join staff as s_nota_venta on s_nota_venta.id_staff=u_nota_venta.id_staff

        left join nota_credito as nota_credito_boleta on nota_credito_boleta.id_boleta=boleta.id_boleta
        left join nota_credito as nota_credito_factura on nota_credito_factura.id_factura=factura.id_factura
 
        where negocio.vigente_negocio=1
        AND (nota_credito_boleta.id_boleta IS NULL AND nota_credito_factura.id_factura IS NULL) ";
        if ($DatosPost->pos_negocio !== '') {
            $consulta .= ' and ';
            $consulta .= " negocio.pos_negocio=$DatosPost->pos_negocio ";
        }
        if ($DatosPost->fechacreacion_negocio_inicio) {
            $consulta .= ' and ';
            $consulta .= " negocio.fechacreacion_negocio>='$DatosPost->fechacreacion_negocio_inicio 00:00:00' ";
        }
        if ($DatosPost->fechacreacion_negocio_fin) {
            $consulta .= ' and ';
            $consulta .= " negocio.fechacreacion_negocio<='$DatosPost->fechacreacion_negocio_fin 23:59:59' ";
        }
        $consulta .= " and (concat(s_nota_venta.nombre_staff,' ',s_nota_venta.apellidopaterno_staff,' ',s_nota_venta.apellidomaterno_staff) like '%$buscar%' or nota_venta.numero_nota_venta LIKE  '%$buscar%' or s_nota_venta.nombre_staff LIKE  '%$buscar%' or s_nota_venta.apellidopaterno_staff LIKE '%$buscar%' or s_nota_venta.apellidomaterno_staff LIKE '%$buscar%' or
        concat(s_boleta.nombre_staff,' ',s_boleta.apellidopaterno_staff,' ',s_boleta.apellidomaterno_staff) like '%$buscar%' or nota_venta.numero_nota_venta LIKE  '%$buscar%' or s_boleta.nombre_staff LIKE  '%$buscar%' or s_boleta.apellidopaterno_staff LIKE '%$buscar%' or s_boleta.apellidomaterno_staff LIKE '%$buscar%' or
        concat(s_factura.nombre_staff,' ',s_factura.apellidopaterno_staff,' ',s_factura.apellidomaterno_staff) like '%$buscar%' or nota_venta.numero_nota_venta LIKE  '%$buscar%' or s_factura.nombre_staff LIKE  '%$buscar%' or s_factura.apellidopaterno_staff LIKE '%$buscar%' or s_factura.apellidomaterno_staff LIKE '%$buscar%' or
        concat(nombre_cliente,' ',apellidopaterno_cliente,' ',apellidomaterno_cliente) like '%$buscar%' or nombre_cliente like '%$buscar%' or
        nota_venta.numero_nota_venta like '%$buscar%' or boleta.numero_boleta like '%$buscar%' or factura.numero_factura like '%$buscar%'
        )
        GROUP BY negocio.id_negocio
        order by negocio.fechacreacion_negocio desc ";
        $ConsultaGlobalLimit = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        $consulta .= "  LIMIT {$longitud} OFFSET $DatosPost->start ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($ConsultaGlobalLimit),
            "recordsFiltered" => count($ConsultaGlobalLimit),
            "data" => $ConsultaGlobal
        );
        echo json_encode($datos);
    }

    public function TraerPagos()
    {
        $ingreso = Ingreso::join('medio_pago', 'medio_pago.id_medio_pago', 'ingreso.id_medio_pago')
            ->join('caja', 'caja.id_caja', 'ingreso.id_caja')
            ->join('staff', 'staff.id_staff', 'caja.id_staff')
            ->where('ingreso.id_negocio', $_GET['id_negocio'])->get();
        echo $ingreso;
    }

    public function MostrarDetalleProducto()
    {
        $negocio = Negocio::join('negocio_detalle', 'negocio_detalle.id_negocio', 'negocio.id_negocio')
            ->join('producto', 'producto.id_producto', 'negocio_detalle.id_producto')
            ->where('negocio.id_negocio', $_GET['id_negocio'])->get();
        echo $negocio;
    }

}
