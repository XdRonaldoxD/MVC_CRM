<?php

use PHPUnit\Framework\TestCase;

require_once "vendor/autoload.php";
require_once "config/Parametros.php";
require_once "config/database.php";
require_once "config/database_mysql.php";
require_once "Helpers/JwtAuth.php";
require_once "Controllers/AnularDocumentoController.php";

class AnularDocumentoTest extends TestCase {

    public function testTraerDocumento() {
        $controller = new AnularDocumentoController();
        $_GET['documento'] = "BOLETA"; // Establece los parámetros de la solicitud
        $_GET['id_documento'] = 1; // Establece los parámetros de la solicitud

        ob_start(); // Captura la salida (output buffering)
        $controller->traerDocumento('BOLETA',91);
        $jsonResponse = ob_get_clean(); // Obtiene la salida JSON

        $data = json_decode($jsonResponse, true);

        $this->assertArrayHasKey('total', $data);
        $this->assertArrayHasKey('igv', $data);
        $this->assertArrayHasKey('subtotal', $data);
        $this->assertArrayHasKey('datos', $data);
        $this->assertArrayHasKey('datos_venta', $data);

        // Realiza aserciones adicionales sobre los datos obtenidos
        $this->assertEquals('Boleta Electronica N ° BR01-60', $data['datos_venta']['documento']);
        // Puedes agregar más aserciones según tus necesidades
    }
}
