<?php

require_once "models/ConsultaGlobal.php";

class ClienteController{

    public function FiltrarCliente(){
        $buscar=$_GET['search'];
        $CONSULTA="SELECT * FROM cliente where vigente_cliente=1 and
        ( concat(nombre_cliente,' ',apellidopaterno_cliente,' ',apellidomaterno_cliente) like '%$buscar%'
           or nombre_cliente like '%$buscar%' or
             apellidopaterno_cliente like '%$buscar%' or
              apellidomaterno_cliente like '%$buscar%'  or
              dni_cliente  like '%$buscar%' or
              ruc_cliente  like '%$buscar%' 
              )";
        $repuesta=(new ConsultaGlobal())->ConsultaGlobal($CONSULTA);
        $data=[];
        foreach ($repuesta as $key => $value) {
            if ($value->tipodocumento_cliente=='DNI') {
                $documento=$value->dni_cliente.'-'.$value->dv_cliente;
            }else{
                $documento=$value->ruc_cliente;
            }
            $elemento=[
                'nombre_cliente'=>$value->nombre_cliente,
                'apellidopaterno_cliente'=>$value->apellidopaterno_cliente,
                'apellidopaterno_cliente'=>$value->apellidopaterno_cliente,
                'documento'=>$documento,
                'id_cliente'=>$value->id_cliente
            ];
            array_push($data,$elemento);
        }
        echo json_encode($data);
    }

}