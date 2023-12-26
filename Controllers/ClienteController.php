<?php

require_once "models/ConsultaGlobal.php";

class ClienteController
{

    public function FiltrarCliente()
    {
        $buscar = $_GET['search'];
        $documento = $_GET['documento'];

        switch ($documento) {
            case 'BOLETA':
                $solo_factura = " tipodocumento_cliente!='RUC' and ";
                break;
            case 'FACTURA':
                $solo_factura = " tipodocumento_cliente='RUC' and ";
                break;
            default:
                $solo_factura = '';
                break;
        }
        $CONSULTA = "SELECT * FROM cliente where vigente_cliente=1 and $solo_factura
        ( concat(nombre_cliente,' ',apellidopaterno_cliente,' ',apellidomaterno_cliente) like '%$buscar%'
           or nombre_cliente like '%$buscar%' or
             apellidopaterno_cliente like '%$buscar%' or
              apellidomaterno_cliente like '%$buscar%'  or
              dni_cliente  like '%$buscar%' or
              ruc_cliente  like '%$buscar%'
              )";
        $repuesta = (new ConsultaGlobal())->ConsultaGlobal($CONSULTA);
        $data = [];
        foreach ($repuesta as $value) {
            if ($value->tipodocumento_cliente === "RUC") {
                $documento = $value->ruc_cliente;
            } else {
                $dv_cliente = '';
                if ($value->dv_cliente) {
                    $dv_cliente = '-' . $value->dv_cliente;
                }
                $documento = $value->dni_cliente . $dv_cliente;
            }
            $elemento = [
                'nombre_cliente' => $value->nombre_cliente,
                'apellidopaterno_cliente' => $value->apellidopaterno_cliente,
                'apellidopaterno_cliente' => $value->apellidopaterno_cliente,
                'documento' => $documento,
                'id_cliente' => $value->id_cliente
            ];
            array_push($data, $elemento);
        }
        echo json_encode($data);
    }
}
