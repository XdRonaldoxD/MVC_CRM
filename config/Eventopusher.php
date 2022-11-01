<?php

define('app_id', '1499388');
define('app_key', '0900f1535d671035b532');
define('app_secret', 'a3b18448f8611ab01cbc');
define('app_cluster', 'us2');
class Eventopusher
{
    public static function conectar()
    {
        $app_id = app_id;
        $app_key =  app_key;
        $app_secret = app_secret;
        $app_cluster = app_cluster;
        $pusher = new Pusher\Pusher($app_key, $app_secret, $app_id, ['cluster' => $app_cluster]);
        return $pusher;
    }
}
