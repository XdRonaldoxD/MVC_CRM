<?php

class Eventopusher
{
    public static function conectar()
    {
        // [SEGURIDAD C5] Credenciales de Pusher leídas de Parametros.php (no versionado).
        $app_id = PUSHER_APP_ID;
        $app_key = PUSHER_APP_KEY;
        $app_secret = PUSHER_APP_SECRET;
        $app_cluster = PUSHER_APP_CLUSTER;
        $pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);
        return $pusher;
    }
}
