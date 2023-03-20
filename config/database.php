<?php

use Illuminate\Database\Capsule\Manager;

$database = new Manager();
$database->addConnection([
    'driver' => 'mysql',
    'host' => $host,
    'database' => $base_datos,
    'username' => $username,
    'password' => $password,
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
// $database->setAsGlobal();
$database->bootEloquent();

