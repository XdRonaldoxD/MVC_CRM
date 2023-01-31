<?php

require_once "models/NotificacionMercadoPago.php";
require_once "models/SuccessMercadoPago.php";

class NotificatationController
{

    public function NotificarMercadoPago()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        if (isset($data->data->id)) {
            $datos = [
                'json_notificacion_mercadopago' => json_encode($data),
                'data_created_id' => $data->data->id,
                'fecha_creacion_notificacion_mercadopago' => date('Y-m-d H:i:s')
            ];
            NotificacionMercadoPago::create($datos);
        }

        echo json_encode('ok');
    }

    public function SuccessMercadoPago()
    {
        $id_notificacion_mercadopago=null;
        $Notificacion=NotificacionMercadoPago::where("data_created_id",$_GET['collection_id'])->first();
        if (isset($Notificacion)) {
            $id_notificacion_mercadopago=$Notificacion->id_notificacion_mercadopago;
        }
        $data = [
            'id_notificacion_mercadopago' => $id_notificacion_mercadopago,
            'collection_id_success_mercadopago' => $_GET['collection_id'],
            'collection_status_success_mercadopago' => $_GET['collection_status'],
            'payment_id_success_mercadopago' => $_GET['payment_id'],
            'status_success_mercadopago' => $_GET['status'],
            'external_reference_success_mercadopago' => $_GET['external_reference'],
            'payment_type_success_mercadopago' => $_GET['payment_type'],
            'merchant_order_id_success_mercadopago' => $_GET['merchant_order_id'],
            'preference_id_success_mercadopago' => $_GET['preference_id'],
            'site_id_success_mercadopago' => $_GET['site_id'],
            'processing_mode_success_mercadopago' => $_GET['processing_mode'],
            'merchant_account_id_success_mercadopago' => $_GET['merchant_account_id'],
            'fecha_creacion_success_mercadopago' => date('Y-m-d H:i:s')
        ];
        SuccessMercadoPago::create($data);
        // echo json_encode('Espere porfavor...');
        header("refresh:0; url=https://www.google.com/");
      
    }
}
