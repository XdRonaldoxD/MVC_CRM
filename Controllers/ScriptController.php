<?php
require_once "models/Bodega.php";
require_once "models/Bodega.php";
require_once "models/Producto.php";
require_once "models/StockProductoBodega.php";
class ScriptController
{
    public function traspasarStockBodegaProducto()
    {
        $bodegas = Bodega::all();
        $productos = Producto::all();
        foreach ($productos as $value) {
            foreach ($bodegas as $bodega) {
                $existe = StockProductoBodega::where('id_bodega', $bodega->id_bodega)->where('id_producto', $value->id_producto)->first();
                if (!$existe) {
                    $datos = [
                        'id_producto' => $value->id_producto,
                        'id_bodega' => $bodega->id_bodega,
                        'total_stock_producto_bodega' => $value->stock_producto,
                        'ultimopreciocompra_stock_producto_bodega' => 0
                    ];
                    StockProductoBodega::create($datos);
                }
            }
        }
        echo json_encode("Traspasdo exitosamente");
    }
}
