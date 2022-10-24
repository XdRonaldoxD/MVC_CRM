<?php

define('app_id', '1280066');
define('app_key', '5fd4b9d2fc8f70b057a0');
define('app_secret', '9836dbb7434df2571930');
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
