<?php

use Illuminate\Database\Capsule\Manager;

$database = new Manager();
$database->addConnection([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'carrito_compras',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
// $database->setAsGlobal();
$database->bootEloquent();

