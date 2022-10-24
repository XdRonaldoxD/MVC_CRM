<?php
require_once "models/Pedido.php";
require_once "models/PedidoDetalle.php";
require_once "models/EstadoPreparacion.php";
require_once "models/EstadoPago.php";
require_once "models/EstadoPedido.php";
require_once "models/ConsultaGlobal.php";


class PedidoController
{

    public function ListarPedidos()
    {
        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $consulta = "";
        if (isset($_POST['id_estado_pago'])) {
            $id_estado_pago = implode($_POST['id_estado_pago']);
            if ($consulta !== "") {
                $condicional = "WHERE";
            } else {
                $condicional = "AND";
            }
            $consulta .= "$condicional estado_pago.id_estado_pago in ($id_estado_pago) ";
        }
        if (isset($_POST['id_estado_pedido'])) {
            $id_estado_pedido = implode($_POST['id_estado_pedido']);
            if ($consulta !== "") {
                $condicional = "WHERE";
            } else {
                $condicional = "AND";
            }
            $consulta .= "$condicional estado_pedido.id_estado_pedido in ($id_estado_pedido) ";
        }
        if (isset($_POST['id_estado_preparacion'])) {
            $id_estado_preparacion = implode($_POST['id_estado_preparacion']);
            if ($consulta !== "") {
                $condicional = "WHERE";
            } else {
                $condicional = "AND";
            }
            $consulta .= "$condicional estado_preparacion.id_estado_preparacion in ($id_estado_preparacion) ";
        }


        $DatosPost = file_get_contents("php://input");
        $DatosPost = json_decode($DatosPost);
        $ConsultaGlobalLimit = (new ConsultaGlobal())->ListarPedido($DatosPost);
        $listaProductos = (new ConsultaGlobal())->ListarPedido($DatosPost,true);
        $datos = array(
            "draw" => $DatosPost->draw,
            "recordsTotal" => count($ConsultaGlobalLimit),
            "recordsFiltered" => count($ConsultaGlobalLimit),
            "data" =>$listaProductos
        );
        echo  json_encode($datos);
    }

    public function FiltrarEstadosPedidos()
    {

        $EstadoPedido = EstadoPedido::where('vigente_estado_pedido', 1)
            ->orderby('orden_estado_pedido')
            ->get();
        $EstadoPreparacion = EstadoPreparacion::where('vigente_estado_preparacion', 1)
            ->orderby('orden_estado_preparacion')
            ->get();
        $EstadoPago = EstadoPago::where('vigente_estado_pago', 1)
            ->orderby('orden_estado_pago')
            ->get();
        $Estados = [
            "EstadoPedido" => $EstadoPedido,
            "EstadoPreparacion" => $EstadoPreparacion,
            "EstadoPago" => $EstadoPago
        ];
        echo json_encode($Estados);
    }

    public function TraerPedido()
    {
        $consulta = " where id_pedido={$_GET['id_pedido']}";
        $PedidoDetalle = (new ConsultaGlobal())->ConsultaPedidoDetalle($consulta);
        $Pedido=Pedido::join('cliente',"cliente.id_cliente","pedido.id_cliente")
        ->where('pedido.id_pedido',$_GET['id_pedido'])
        ->select('pedido.*',"cliente.dni_cliente","cliente.nombre_cliente","cliente.apellidopaterno_cliente",
        "cliente.apellidomaterno_cliente","cliente.e_mail_cliente","cliente.telefono_cliente","cliente.celular_cliente")
        ->first();
        $EstadoPedido=EstadoPedido::all();
        $EstadoPreparacion=EstadoPreparacion::all();
        $datos=[
            "Pedido"=>$Pedido,
            "PedidoDetalle"=>$PedidoDetalle,
            "EstadoPedido"=>$EstadoPedido,
            "EstadoPreparacion"=>$EstadoPreparacion
        ];
        echo json_encode($datos);
    }
}
