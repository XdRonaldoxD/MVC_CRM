<?php

use Illuminate\Database\Capsule\Manager;

$conexion=[
    'driver' => 'mysql',
    'host' => $host,
    'database' => $base_datos,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
];
var_dump($conexion);
die;
$database = new Manager();
$database->addConnection($conexion);
// $database->setAsGlobal();
$database->bootEloquent();

