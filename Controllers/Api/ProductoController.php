<?php

// if (isset($_GET['eloquent'])) {
require_once "models/Producto.php";
require_once "models/ProductoColor.php";
require_once "models/CategoriaProducto.php";
require_once "models/DetalleZonaOferta.php";
require_once "models/PedidoDetalle.php";
require_once "models/ProductoImagen.php";
require_once "models/Categorias.php";
require_once "models/AtributoProducto.php";

// } else {
require_once "models/ConsultaGlobal.php";
// }
class ProductoController
{

    // protected $ConsultaApi;
    // public function __construct()
    // {
    //     $this->ConsultaApi = new ConsultaGlobal();

    // }
    public function ProductosVendidoOferta()
    {
        $arreglos = [];
        //PRODUCTOS MAS VENDIDOS
        $productos = PedidoDetalle::join("producto", "producto.id_producto", "pedido_detalle.id_producto")
            ->select("producto.*")
            ->groupby('pedido_detalle.id_producto')
            ->orderby('pedido_detalle.id_producto', 'desc')
            ->take($_GET['limit'])
            ->get()
            ->toArray();
        //FIN
        $cantidad_sobra = $_GET['limit'] - count($productos);
        if ($cantidad_sobra > 0) {
            $id_producto = [];
            foreach ($productos as $key => $value) {
                array_push($id_producto, $value['id_producto']);
            }
            $productos_sobrantes = Producto::select("*")
                ->wherenotin("id_producto", $id_producto)
                ->where('stock_producto', '>', 0)
                ->take($cantidad_sobra)
                ->get()
                ->toarray();
            $productos = array_merge($productos, $productos_sobrantes);
            // FOTO PRINCIPAL,SKU, GLOSA,PRECIO VENTA
            // dd($productos);
        }
        foreach ($productos as $key => $element) {
            $imagen_base_64 = ProductoImagen::where("id_producto", $element['id_producto'])->pluck('url_producto_imagen');
            // $imagen_base_64 = [];
            // foreach ($imagenes as $key => $value) {
            //     //TRAER EL PROTOCOLO Y DOMINiO AL FINAL UNIRLO(NO FUNCIONA PUBLIC PATH solo laravel)
            //     $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
            //     $domain = $_SERVER['HTTP_HOST'];
            //     $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/$value";
            //     array_push($imagen_base_64, $imagens);
            // }


            $producto_color = ProductoColor::where("id_producto", $element['id_producto'])->get()->toArray();
            $categorias = CategoriaProducto::join("categoria", "categoria.id_categoria", "categoria_producto.id_categoria")
                ->where("id_producto", $element['id_producto'])->get()->toArray();
            $color_producto = [];
            if (!empty($producto_color)) {
                foreach ($producto_color as $key => $elemento) {
                    $datos = [
                        "name" => $elemento['nombre_producto_color'],
                        "slug" => "",
                        "hexadecimal" => $elemento['hexadecimal_producto_color'],
                        "customFields" => [],
                        'id_producto_color' => $elemento['id_producto_color']
                    ];
                    array_push($color_producto, $datos);
                }
            }
            $categorias_arreglo = [];
            foreach ($categorias as $key => $categoria) {
                $dato_categoria = [
                    "id" => $categoria['id_categoria'],
                    "type" => "shop",
                    "name" => $categoria['glosa_categoria'],
                    "slug" => "screwdrivers",
                    "path" => "instruments/power-tools/screwdrivers",
                    "image" => null,
                    "items" => 126,
                    "customFields" => [],
                    "parents" => null,
                    "children" => null
                ];
                array_push($categorias_arreglo, $dato_categoria);
            }
            $atributoProducto = AtributoProducto::join('atributo', 'atributo.id_atributo', 'atributo_producto.id_atributo')
                ->where('id_producto', $element['id_producto'])->get();
            $atributo_producto = [];
            foreach ($atributoProducto as $key => $data) {
                $elementos = [
                    "id_atributo_producto" => $data->id_atributo_producto,
                    "glosa_atributo" => $data->glosa_atributo,
                ];
                array_push($atributo_producto, $elementos);
            }

            $fecha_actual = date("Y-m-d");
            $fecha_producto = date('Y-m-d', strtotime($element['fechacreacion_producto'] . "+ 2 days"));
            $new = [];
            if (strtotime($fecha_producto) > strtotime($fecha_actual)) {
                $new = ["new"];
            }
            $datosProductos = [
                "atributo_producto" => $atributo_producto,
                "id" => $element['id_producto'],
                "name" => $element['glosa_producto'],
                "sku" => $element['codigo_producto'],
                "slug" => $element['urlamigable_producto'],
                "price" => $element['precioventa_producto'],
                "stock" => $element['stock_producto'],
                "descripcion" => $element['detallelargo_producto'],
                "compareAtPrice" => null,
                "images" => $imagen_base_64,
                "badges" => $new,
                "rating" => 4,
                "reviews" => 12,
                "availability" => "in-stock",
                "brand" => [
                    "name" => "Brandix",
                    "slug" => "brandix",
                    "image" => "assets/images/logos/logo-1.png",
                    "id" => 1
                ],
                "categories" => [
                    $categorias_arreglo
                ],
                "attributes" => [
                    [
                        "name" => "Color",
                        "slug" => "color",
                        "featured" => false,
                        "values" =>
                        $color_producto,
                        "customFields" => []
                    ],
                    [
                        "name" => "Speed",
                        "slug" => "speed",
                        "featured" => true,
                        "values" => [
                            [
                                "name" => "750 RPM",
                                "slug" => "750-rpm",
                                "customFields" => []
                            ]
                        ],
                        "customFields" => []
                    ],
                    [
                        "name" => "Power Source",
                        "slug" => "power-source",
                        "featured" => true,
                        "values" => [
                            [
                                "name" => "Cordless-Electric",
                                "slug" => "cordless-electric",
                                "customFields" => []
                            ]
                        ],
                        "customFields" => []
                    ],
                    [
                        "name" => "Battery Cell Type",
                        "slug" => "battery-cell-type",
                        "featured" => true,
                        "values" => [
                            [
                                "name" => "Lithium",
                                "slug" => "lithium",
                                "customFields" => []
                            ]
                        ],
                        "customFields" => []
                    ],
                    [
                        "name" => "Voltage",
                        "slug" => "voltage",
                        "featured" => true,
                        "values" => [
                            [
                                "name" => "20 Volts",
                                "slug" => "20-volts",
                                "customFields" => []
                            ]
                        ],
                        "customFields" => []
                    ],
                    [
                        "name" => "Battery Capacity",
                        "slug" => "battery-capacity",
                        "featured" => true,
                        "values" => [
                            [
                                "name" => "2 Ah",
                                "slug" => "2-Ah",
                                "customFields" => []
                            ]
                        ],
                        "customFields" => []
                    ]
                ],
                "customFields" => []
            ];
            array_push($arreglos, $datosProductos);
        }
        echo json_encode($arreglos);
        die;
        //FIN PRODUCTOS MAS VENDIDOS
        //MOSTRAMOS LOS PRODUCTOS CON OFERTAS
        $fechaActual = date('Y-m-d H:i:s');
        $productosofertas = DetalleZonaOferta::join("zona_oferta", "zona_oferta.id_zona_oferta", "=", "detalle_zona_oferta.id_zona_oferta")
            ->join("producto", 'producto.id_producto', 'detalle_zona_oferta.id_producto')
            ->where('zona_oferta.vigente_zona_oferta', 1)
            ->where('zona_oferta.fechainicio_zona_oferta', '<=', $fechaActual)
            ->where('zona_oferta.fechatermino_zona_oferta', '>=', $fechaActual)
            ->get()
            ->toarray();
        $productosofe = array();
        foreach ($productosofertas as $producto) {
            // SE CALCULA LA FECHA DE TERIMO DE LA OFERTA
            $datos = [
                'id_producto' => $producto->id_producto,
                'glosa_producto' => $producto->glosa_producto,
                "urlamigable" => $producto->urlamigable_producto,
                'stock_producto' =>  $producto->stock_producto,
                'producto_oferta' => 1,
                'precioventa_producto' => $producto->precioventa_producto,
                'dimensiones_paquete' => [
                    'ancho' => $producto->anchopaquete_producto,
                    'alto' => $producto->altopaquete_producto,
                    'profundidad' => $producto->profundidadpaquete_producto,
                    'peso' => $producto->pesopaquete_producto,
                ]
            ];
            array_push($productosofe, $datos);
        }
        // FIN DE LOS PRODUCTOS OFERTAS
        $prod = array();
        foreach ($productos as $producto) {
            $portada = ProductoImagen::where('id_producto', $producto['id_producto'])->where('portada_producto_imagen', 1)->first();
            $descuento = 0;
            $compareAtPrice = 0;
            $data = [];
            //VERIFICAMOS LOS PRODUCTOS CON OFERTAS
            foreach ($productosofe as $key => $productoofera) {
                if ($productoofera['id_producto'] === $producto['id_producto']) {
                    $descuento = $productoofera['porcentajedescuento_detalle_zona_oferta'];
                    $compareAtPrice = $productoofera['preciolista_detalle_zona_oferta'];
                    $price = $productoofera['precioventa_producto'];
                    $data += ["producto_oferta" => 1];
                }
            }
            $data += [
                'id_producto' => $producto['id_producto'],
                'glosa_producto' => $producto['glosa_producto'],
                "urlamigable" => $producto['urlamigable_producto'],
                'precioventa_producto' => $producto['urlamigable_producto'],
                'compareAtPrice' => $compareAtPrice,
                'descuento' => $descuento,
                'path_producto' => $portada,
                'dimensiones_paquete' => [
                    'ancho' => $producto['anchopaquete_producto'],
                    'alto' => $producto['altopaquete_producto'],
                    'profundidad' => $producto['profundidadpaquete_producto'],
                    'peso' => $producto['pesopaquete_producto'],
                ]
            ];
            array_push($prod, $data);
        }
        echo json_encode($prod);
    }
    public function listarProductos()
    {

        $condicion = "SELECT producto.*,
            (SELECT GROUP_CONCAT(id_producto SEPARATOR '~') from producto_relacionado where idproductopadre_producto_relacionado=producto.id_producto) as producto_relacionado,
            (SELECT GROUP_CONCAT(categoria.id_categoria,'@',categoria.glosa_categoria SEPARATOR '~') 
            from categoria_producto
            inner join categoria on categoria.id_categoria = categoria_producto.id_categoria
            where id_producto=producto.id_producto) 
            as categorias,
            (SELECT GROUP_CONCAT(producto_imagen.url_producto_imagen SEPARATOR '~') 
            from producto_imagen where id_producto=producto.id_producto
            ) as producto_imagen,
            (select GROUP_CONCAT(nombre_producto_color,',',hexadecimal_producto_color,',',id_producto_color SEPARATOR '~')
            FROM producto_color where id_producto=producto.id_producto
            ) as color_producto,
            (SELECT GROUP_CONCAT(glosa_especificaciones_producto,',',respuesta_especificaciones_producto SEPARATOR '~') 
            from especificaciones_producto where id_producto=producto.id_producto
            ) as especificacion_producto,
            (SELECT GROUP_CONCAT(atributo.glosa_atributo,',',atributo_producto.id_atributo_producto SEPARATOR '~') 
            from atributo_producto
            inner join atributo using (id_atributo)
            where id_producto=producto.id_producto
            ) as atributo_producto
            from producto
            where vigente_producto=1 and visibleonline_producto=1 and stock_producto>0
            limit 100";
        $ConsultaApi = new ConsultaGlobal();
        $Productos = $ConsultaApi->ConsultaGlobal($condicion);
        $data = [];
        foreach ($Productos as $key => $value) {
            $fecha_actual = date("Y-m-d");
            $fecha_producto = date('Y-m-d', strtotime($value->fechacreacion_producto . "+ 5 months"));
            if (strtotime($fecha_producto) > strtotime($fecha_actual)) {
                $arreglos = $this->ConstruirProducto($value);
                array_push($data, $arreglos[0]);
            }
        }
        echo json_encode($data);
    }

    public function ListarCategoriaProductoApi()
    {
        $ConsultaApi = new ConsultaGlobal();
        $ConsultaApi->LiberarGroupConcat();
        //TRAEMOS TODAS LA CATEGORIAS SI ES PADRE EN CASO DE QUE NO TENGA SERA LAS ULTIMAS
        //CATEGORIAS HIJAS (EMBASE A ESTAS CATEGORIAS SE TRAE TODOS LOS PRODUCTOS RELACIONADOS)
        $recorrer = true;
        $categoria_select = Categorias::where('urlamigable_categoria', $_GET['urlamigable_categoria'])->first();

        $id_categoria = $categoria_select->id_categoria;
        $hijos = [];

        while ($recorrer) {
            $categorias = Categorias::where('id_categoria_padre', $id_categoria)->get();

            if (count($categorias) > 1) {
                foreach ($categorias as $key => $elements) {
                    $categorias_sub_hijo = Categorias::where('id_categoria_padre', $elements->id_categoria)->get();
                    if (count($categorias_sub_hijo) > 0) {
                        foreach ($categorias_sub_hijo as $key => $elementos) {
                            array_push($hijos, $elementos->id_categoria);
                        }
                    } else {
                        array_push($hijos, $elements->id_categoria);
                    }
                }
                $recorrer = false;
            } else if (count($categorias) == 1) {
                $id_categoria = $categorias[0]->id_categoria;
            } else {
                $categoria = Categorias::where('id_categoria', $id_categoria)->first();
                array_push($hijos, $categoria->id_categoria);
                $recorrer = false;
            }
        }

        $hijos_ = array_unique($hijos);
        $hijos = implode(',', $hijos_);

        // ------------------------------------------
        $condicion_filter = "where categoria_producto.id_categoria in ($hijos)";

        $consulta_filter = $ConsultaApi->EstructuraFilterApi($condicion_filter);
        $Producto = $ConsultaApi->ListarCategoriaProductoApi($condicion_filter);
        $arreglo_check = [];
        foreach ($hijos_ as $key => $value) {
            $categoria_producto = CategoriaProducto::join('categoria', 'categoria.id_categoria', 'categoria_producto.id_categoria')
                ->where('categoria_producto.id_categoria', $value)->get();
            if (count($categoria_producto) > 0) {
                $dato = [
                    "slug" => $categoria_producto[0]->urlamigable_categoria,
                    "name" => $categoria_producto[0]->glosa_categoria,
                    "count" => count($categoria_producto)
                ];
                array_push($arreglo_check, $dato);
            }
        }

        $estructura_filtar = [
            [
                "type" => "range",
                "slug" => "price",
                "name" => "Price",
                "value" => [
                    0,
                    $consulta_filter->precio_mayor + 1,
                ],
                "min" =>  0,
                "max" => $consulta_filter->precio_mayor + 1,
            ],
            [
                "type" => "check",
                "slug" => "brand",
                "name" => "Brand",
                "value" => [],
                "items" => $arreglo_check
            ],

        ];
        $datos = [];
        foreach ($Producto as $key => $element) {
            $arreglo = $this->ConstruirProducto($element);
            array_push($datos, $arreglo[0]);
        }
        $respuesta = [
            'data_productos' => $datos,
            'filters' => $estructura_filtar
        ];
        echo json_encode($respuesta);
    }

    public function TraerProductoSlug()
    {
        $ConsultaApi = new ConsultaGlobal();
        $ConsultaApi->LiberarGroupConcat();
        $condicion = "where urlamigable_producto='{$_POST['urlamigable_producto']}' ";
        $Producto = $ConsultaApi->ListarProductoApi($condicion);
        $arreglos = $this->ConstruirProducto($Producto);
        echo json_encode($arreglos[0]);
    }

    public function ConstruirProducto($element)
    {
        $producto_relacionado_arreglo = [];
        //TRAEMOS PRIMEROS LOS DATOS DE LOS PRODUCTOS RELACIOANDO CON LA MISTRA ESTRUCTURA DEL TRAER PRODUCTO POR US URL
        if ($element->producto_relacionado) {
            $producto_relacionado = $element->producto_relacionado;
            $producto_relacionado = explode('~', $producto_relacionado);
            foreach ($producto_relacionado as $key => $id_producto) {
                $consulta = " WHERE id_producto= {$id_producto}  ";
                $ConsultRelacionado = (new ConsultaGlobal())->ListarProductoApi($consulta);
                $imagenes_relacion_relacion = [];
                $categorias_arreglo_relacion = [];
                $color_producto_relacion = [];
                $especificacion_producto_relacion = [];
                $categorias_relacion = [];
                $colores = [];
                $especificacion = [];
                $atributo_producto_relacion = [];
                if ($ConsultRelacionado->atributo_producto) {
                    $atributo_pro = explode("~", $ConsultRelacionado->atributo_producto);
                    foreach ($atributo_pro as $key => $value) {
                        $separador = explode(",", $value);
                        $elementos = [
                            "id_atributo_producto" => $separador[1],
                            "glosa_atributo" => $separador[0],
                        ];
                        array_push($atributo_producto_relacion, $elementos);
                    }
                }
                if ($ConsultRelacionado->producto_imagen) {
                    $imagenes_relacion_relacion = explode("~", $ConsultRelacionado->producto_imagen);
                    // foreach ($imagen as $key => $value) {

                    //     $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                    //     $domain = $_SERVER['HTTP_HOST'];
                    //     $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/{$value}";

                    //     array_push($imagenes_relacion_relacion, $imagens);
                    // }
                } else {
                    $imagenes_relacion_relacion = [
                        "assets/images/products/product-1-1.jpg",
                        "assets/images/products/product-3.jpg"
                    ];
                }
                if ($ConsultRelacionado->categorias) {
                    $categorias_relacion = explode("~", $ConsultRelacionado->categorias);
                }
                foreach ($categorias_relacion as $key => $categoria) {
                    $separador = explode("@", $categoria);
                    $id_categoria = $separador[0];
                    $nombre_categoria = $separador[0];
                    $dato_categoria = [
                        "id" => $id_categoria,
                        "type" => "shop",
                        "name" => $nombre_categoria,
                        "slug" => "screwdrivers",
                        "path" => "instruments/power-tools/screwdrivers",
                        "image" => null,
                        "items" => 126,
                        "customFields" => [],
                        "parents" => null,
                        "children" => null
                    ];
                    array_push($categorias_arreglo_relacion, $dato_categoria);
                }

                if ($ConsultRelacionado->color_producto) {
                    $colores = explode("~", $ConsultRelacionado->color_producto);
                } else {
                    $colores =  [];
                }

                foreach ($colores as $key => $elementos) {
                    $color = explode(",", $elementos);
                    $elemento =  [
                        "name" => $color[0],
                        "slug" => "yellow",
                        "hexadecimal" => $color[1],
                        "customFields" => [],
                        'id_producto_color' => $color[2]
                    ];
                    array_push($color_producto_relacion, $elemento);
                }

                if ($ConsultRelacionado->especificacion_producto) {
                    $especificacion = explode("~", $ConsultRelacionado->especificacion_producto);
                }

                foreach ($especificacion as $key => $especifica) {
                    $especifica = explode(",", $especifica);
                    $elementos = [
                        "name" => isset($especifica[0]) ? $especifica[0] : '',
                        "value" =>  isset($especifica[1]) ? $especifica[1] : ''
                    ];
                    array_push($especificacion_producto_relacion, $elementos);
                }

                $fecha_actual_relacionado = date("Y-m-d");
                $fecha_producto_relacionado = date('Y-m-d', strtotime($ConsultRelacionado->fechacreacion_producto . "+ 2 days"));
                $new_relacionado = [];
                if (strtotime($fecha_producto_relacionado) > strtotime($fecha_actual_relacionado)) {
                    $new_relacionado = ["new"];
                }
                $datos = [
                    "id" => $ConsultRelacionado->id_producto,
                    "name" => $ConsultRelacionado->glosa_producto,
                    "sku" => $ConsultRelacionado->codigo_producto,
                    "slug" => $ConsultRelacionado->urlamigable_producto,
                    "price" => $ConsultRelacionado->precioventa_producto,
                    "stock" => $ConsultRelacionado->stock_producto,
                    "descripcion" => $ConsultRelacionado->detalle_producto,
                    "detallelargo_producto" => $ConsultRelacionado->detallelargo_producto,
                    "compareAtPrice" => null,
                    "images" => $imagenes_relacion_relacion,
                    "atributo_producto" => $atributo_producto_relacion,
                    "especificaciones" => $especificacion_producto_relacion,
                    "badges" => $new_relacionado,
                    "rating" => 4,
                    "reviews" => 12,
                    "availability" => "in-stock",
                    "brand" => [
                        "name" => "Brandix",
                        "slug" => "brandix",
                        "image" => "assets/images/logos/logo-1.png",
                        "id" => 1
                    ],
                    "categories" => [
                        $categorias_arreglo_relacion
                    ],
                    "attributes" => [
                        [
                            "name" => "Color",
                            "slug" => "color",
                            "featured" => false,
                            "values" =>
                            $color_producto_relacion,
                            "customFields" => []
                        ],
                        [
                            "name" => "Speed",
                            "slug" => "speed",
                            "featured" => true,
                            "values" => [
                                [
                                    "name" => "750 RPM",
                                    "slug" => "750-rpm",
                                    "customFields" => []
                                ]
                            ],
                            "customFields" => []
                        ],
                        [
                            "name" => "Power Source",
                            "slug" => "power-source",
                            "featured" => true,
                            "values" => [
                                [
                                    "name" => "Cordless-Electric",
                                    "slug" => "cordless-electric",
                                    "customFields" => []
                                ]
                            ],
                            "customFields" => []
                        ],
                        [
                            "name" => "Battery Cell Type",
                            "slug" => "battery-cell-type",
                            "featured" => true,
                            "values" => [
                                [
                                    "name" => "Lithium",
                                    "slug" => "lithium",
                                    "customFields" => []
                                ]
                            ],
                            "customFields" => []
                        ],
                        [
                            "name" => "Voltage",
                            "slug" => "voltage",
                            "featured" => true,
                            "values" => [
                                [
                                    "name" => "20 Volts",
                                    "slug" => "20-volts",
                                    "customFields" => []
                                ]
                            ],
                            "customFields" => []
                        ],
                        [
                            "name" => "Battery Capacity",
                            "slug" => "battery-capacity",
                            "featured" => true,
                            "values" => [
                                [
                                    "name" => "2 Ah",
                                    "slug" => "2-Ah",
                                    "customFields" => []
                                ]
                            ],
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ];
                array_push($producto_relacionado_arreglo, $datos);
            }
        }
        //--------------------------------------------------------------------

        $arreglos = [];
        $imagenes = [];
        $categorias_arreglo = [];
        $color_producto = [];
        $especificacion_producto = [];
        $categorias = [];
        $colores = [];
        $especificacion = [];
        $atributo_producto = [];


        if ($element->atributo_producto) {
            $atributo_pro = explode("~", $element->atributo_producto);
            foreach ($atributo_pro as $key => $value) {
                $separador = explode(",", $value);
                $elementos = [
                    "id_atributo_producto" => $separador[1],
                    "glosa_atributo" => $separador[0],
                ];

                array_push($atributo_producto, $elementos);
            }
        }
        if ($element->producto_imagen) {
            $imagenes = explode("~", $element->producto_imagen);
            // foreach ($imagen as $key => $value) {
            //     // $path_producto_imagen = __DIR__ . "/../../archivo/imagen_producto/{$value}";
            //     // $path_producto_imagen = base64_encode(file_get_contents($path_producto_imagen));
            //     // $elementos = [
            //     //     'data:image/png;base64,' . $path_producto_imagen,
            //     // ];

            //     $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
            //     $domain = $_SERVER['HTTP_HOST'];
            //     $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/{$value}";
            //     array_push($imagenes, $imagens);
            // }
        } else {
            $imagenes = [
                "assets/images/products/product-1-1.jpg",
                "assets/images/products/product-3.jpg"
            ];
        }
        if ($element->categorias) {
            $categorias = explode("~", $element->categorias);
        }

        foreach ($categorias as $key => $categoria) {
            $separador = explode("@", $categoria);
            $id_categoria = $separador[0];
            $nombre_categoria = $separador[0];
            $dato_categoria = [
                "id" => $id_categoria,
                "type" => "shop",
                "name" => $nombre_categoria,
                "slug" => "screwdrivers",
                "path" => "instruments/power-tools/screwdrivers",
                "image" => null,
                "items" => 126,
                "customFields" => [],
                "parents" => null,
                "children" => null
            ];
            array_push($categorias_arreglo, $dato_categoria);
        }

        if ($element->color_producto) {
            $colores = explode("~", $element->color_producto);
        } else {
            $colores =  [];
        }

        foreach ($colores as $key => $elementos) {
            $color = explode(",", $elementos);
            $elemento =  [
                "name" => $color[0],
                "slug" => "yellow",
                "hexadecimal" => $color[1],
                "customFields" => [],
                'id_producto_color' => $color[2]
            ];
            array_push($color_producto, $elemento);
        }

        if ($element->especificacion_producto) {
            $especificacion = explode("~", $element->especificacion_producto);
        }

        foreach ($especificacion as $key => $especifica) {
            $especifica = explode(",", $especifica);
            $elementos = [
                "name" => isset($especifica[0]) ? $especifica[0] : '',
                "value" =>  isset($especifica[1]) ? $especifica[1] : ''
            ];
            array_push($especificacion_producto, $elementos);
        }

        $fecha_actual = date("Y-m-d");
        $fecha_producto = date('Y-m-d', strtotime($element->fechacreacion_producto . "+ 5 months"));
        $new = [];
        if (strtotime($fecha_producto) > strtotime($fecha_actual)) {
            $new = ["new"];
        }
        $datos = [
            "id" => $element->id_producto,
            "name" => $element->glosa_producto,
            "sku" => $element->codigo_producto,
            "slug" => $element->urlamigable_producto,
            "price" => $element->precioventa_producto,
            "stock" => $element->stock_producto,
            "descripcion" => $element->detalle_producto,
            "detallelargo_producto" => $element->detallelargo_producto,
            "compareAtPrice" => null,
            "images" => $imagenes,
            "atributo_producto" => $atributo_producto,
            'producto_relacionado' => $producto_relacionado_arreglo,
            "especificaciones" => $especificacion_producto,
            "badges" => $new,
            "rating" => 4,
            "reviews" => 12,
            "availability" => "in-stock",
            "brand" => [
                "name" => isset($element->glosa_categoria) ? $element->glosa_categoria : "Brandix",
                "slug" => isset($element->urlamigable_categoria) ? $element->urlamigable_categoria : "brandix",
                "image" => "assets/images/logos/logo-1.png",
                "id" => 1
            ],
            "categories" => [
                $categorias_arreglo
            ],
            "attributes" => [
                [
                    "name" => "Color",
                    "slug" => "color",
                    "featured" => false,
                    "values" =>
                    $color_producto,
                    "customFields" => []
                ],
                [
                    "name" => "Speed",
                    "slug" => "speed",
                    "featured" => true,
                    "values" => [
                        [
                            "name" => "750 RPM",
                            "slug" => "750-rpm",
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ],
                [
                    "name" => "Power Source",
                    "slug" => "power-source",
                    "featured" => true,
                    "values" => [
                        [
                            "name" => "Cordless-Electric",
                            "slug" => "cordless-electric",
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ],
                [
                    "name" => "Battery Cell Type",
                    "slug" => "battery-cell-type",
                    "featured" => true,
                    "values" => [
                        [
                            "name" => "Lithium",
                            "slug" => "lithium",
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ],
                [
                    "name" => "Voltage",
                    "slug" => "voltage",
                    "featured" => true,
                    "values" => [
                        [
                            "name" => "20 Volts",
                            "slug" => "20-volts",
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ],
                [
                    "name" => "Battery Capacity",
                    "slug" => "battery-capacity",
                    "featured" => true,
                    "values" => [
                        [
                            "name" => "2 Ah",
                            "slug" => "2-Ah",
                            "customFields" => []
                        ]
                    ],
                    "customFields" => []
                ]
            ],
            "customFields" => []
        ];
        array_push($arreglos, $datos);


        return $arreglos;
    }

    public function FiltrarProductoCategoria()
    {
        $id_categoria = $_POST['id_categoria'];
        $buscar = $_POST['query'];
        $productos = Producto::where("producto.vigente_producto", 1);
        if ($id_categoria !== "") {
            $productos = $productos->join("categoria_producto", "producto.id_producto", "categoria_producto.id_categoria")
                ->where('categoria_producto.id_categoria', $id_categoria);
        }
        $productos = $productos->where(function ($query) use ($buscar) {
            $query->where('producto.glosa_producto', 'LIKE', "%$buscar%")
                ->orWhere('producto.codigo_producto', 'LIKE', "%$buscar%")
                ->orWhere('producto.codigo_barra_producto', 'LIKE', "%$buscar%");
        })->take(50)->get();
        $array = [];
        foreach ($productos as $key => $value) {

            $producto_imagen = ProductoImagen::where('portada_producto_imagen', 1)->where('id_producto', $value->id_producto)->first();
            if (isset($producto_imagen)) {
                $path_producto_imagen = __DIR__ . "/../../archivo/imagen_producto/{$producto_imagen->path_producto_imagen}";
                if (is_file($path_producto_imagen)) {
                    $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
                    $domain = $_SERVER['HTTP_HOST'];
                    $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_producto/$producto_imagen->path_producto_imagen";
                } else {
                    $imagens = 'assets/images/products/product-1.jpg';
                }
            } else {
                $imagens = 'assets/images/products/product-1.jpg';
            }


            $element = [
                "id" => $value->id_producto,
                "name" => $value->glosa_producto,
                "sku" => $value->codigo_producto,
                "slug" =>  $value->urlamigable_producto,
                "price" => $value->precioventa_producto,
                "compareAtPrice" => null,
                "images" => [
                    $imagens
                ],
                "badges" => [
                    "new"
                ],
                "rating" => 4,
                "reviews" => 12,
                "availability" => "in-stock",
                "brand" => [
                    "name" => "Brandix",
                    "slug" => "brandix",
                    "image" => "assets/images/logos/logo-1.png",
                    "id" => 1
                ],
                "categories" => [],
                "attributes" => [],
                "customFields" => []
            ];
            array_push($array, $element);
        }
        echo json_encode($array);
    }

    public function TodasCategoriaPadre()
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_categoria";
        $consulta = " SELECT *,if(pathimagen_categoria is null,null,CONCAT('$imagens','/',pathimagen_categoria))  as pathimagen_categoria FROM  categoria 
        where vigente_categoria=1 and 
        visibleonline_categoria=1 and 
        id_categoria_padre=0 order by orden_categoria";
        $Categorias = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        echo json_encode($Categorias);
    }

    public function MegaMenuProductos()
    {
        $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
        $domain = $_SERVER['HTTP_HOST'];
        $imagens = $protocol . $domain . "/MVC_CRM/archivo/imagen_categoria";
        $consulta = " SELECT *,if(pathimagen_categoria is null,null,CONCAT('$imagens','/',pathimagen_categoria))  as pathimagen_categoria FROM  categoria 
            where vigente_categoria=1 and 
            visibleonline_categoria=1 and 
            id_categoria_padre=0 order by orden_categoria";
        $Categorias = (new ConsultaGlobal())->ConsultaGlobal($consulta);
        $datos = [];
        foreach ($Categorias as $cat) {
            $Categorias_sub_1 = Categorias::select('*')
                ->where('id_categoria_padre', $cat->id_categoria)
                ->get();
            $columnas = [];
            foreach ($Categorias_sub_1 as $key => $element) {
                $Categorias_sub_hijos = Categorias::select('*')
                    ->where('id_categoria_padre', $element->id_categoria)
                    ->get();
                $items = [];
                foreach ($Categorias_sub_hijos as $key => $value) {
                    $datos_sub_hijo_2 = [
                        "label" => $value->glosa_categoria,
                        "url" => "/shop/catalog/$value->urlamigable_categoria"
                    ];
                    array_push($items, $datos_sub_hijo_2);
                }
                $size = self::Medidas(count($Categorias_sub_1));
                $datos_principal = [
                    "size" => $size['sizeCol'],
                    "items" => [[
                        "label" => $element->glosa_categoria,
                        "url" => "/shop/catalog/$element->urlamigable_categoria",
                        "items" => $items
                    ]]
                ];
                array_push($columnas, $datos_principal);
            }
            $arreglo_categoria = [
                "label" => "$cat->glosa_categoria",
                "url" => "/shop/catalog/$cat->urlamigable_categoria",
                "menu" => [
                    "type" => "megamenu",
                    "size" => "xl",
                    "image" => ($cat->pathimagen_categoria !== null) ? $cat->pathimagen_categoria  : "assets/images/megamenu/megamenu-1.jpg",
                    "columns" => $columnas
                ]

            ];
            array_push($datos, $arreglo_categoria);
        }
        echo json_encode($datos);
    }

    public function MegaMenuProductosMobile()
    {
        $Categorias_padres = Categorias::select('*')
            ->where('vigente_categoria', 1)
            ->where('visibleonline_categoria', 1)
            ->where('id_categoria_padre', 0)
            ->orderBy('orden_categoria')
            ->get();
        $arbol_mega_menu = [];
        foreach ($Categorias_padres as $key => $element) {
            $subhijo = true;
            $id_padre = $element->id_categoria;
            $arreglo_children = [];
            while ($subhijo) {
                $Categorias_hijos = Categorias::select('*')
                    ->where('id_categoria_padre', $id_padre)
                    ->first();
                if (isset($Categorias_hijos)) {
                    $datos = [
                        'type' => 'link',
                        'label' => $Categorias_hijos->glosa_categoria,
                        'url' => '/'
                    ];
                    array_push($arreglo_children, $datos);
                    $id_padre = $Categorias_hijos->id_categoria;
                } else {
                    $datos_padre = [
                        'type' => 'link',
                        'label' => $element->glosa_categoria,
                        'url' => '/'
                    ];
                    $tiene_hijo = Categorias::select('*')
                        ->where('id_categoria_padre', $element->id_categoria)
                        ->first();
                    if (isset($tiene_hijo)) {
                        $datos_padre += [
                            'children' => $arreglo_children
                        ];
                        array_push($arbol_mega_menu, $datos_padre);
                    }

                    $subhijo = false;
                }
            }

            // ['type' => 'link', 'label' => 'Home', 'url' => '/', 'children' => [
            //     ['type' => 'link', 'label' => 'Home 1', 'url' => '/'],
            //     ['type' => 'link', 'label' => 'Home 2', 'url' => '/home-two'],
            //     ['type' => 'link', 'label' => 'Offcanvas Cart', 'url' => '/offcanvas-cart']
            // ]];
        }
        echo json_encode($arbol_mega_menu);
    }


    public static function CantidadPadres($id_categoria, $cantidad)
    {
        $modulo =  Categorias::select('id_categoria_padre', 'id_categoria')
            ->where('id_categoria', $id_categoria)
            ->where('visibleonline_categoria', 1)
            ->first();
        if ($modulo['id_categoria_padre']  == 0) {
            $cantidad = 1;
        } else {
            $cantidad = $cantidad + self::CantidadPadres($modulo['id_categoria_padre'], $cantidad++);
        }
        return $cantidad;
    }

    /* MENU MEGAMENU */

    public static function Medidas($columnas)
    {
        switch ($columnas) {
            case 1:
                $size = "sm";
                $sizeCol = "12";
                break;
            case 2:
                $size = "nl";
                $sizeCol = "6";
                break;
            case 3:
                $size = "md";
                $sizeCol = "4";
                break;
            case 4:
                $size = "lg";
                $sizeCol = "3";
                break;
            case 5:
                $size = "xl";
                $sizeCol = "3";
                break;
            default:
                $size = "xl";
                $sizeCol = "3";
                break;
        }
        return [
            'size' => $size,
            'sizeCol' => $sizeCol,
        ];
    }

    public function CategoriaPopulares()
    {
        $Consulta = "SELECT categoria.*, COUNT(id_producto) AS total_productos
        FROM categoria_producto
        INNER JOIN categoria using (id_categoria)
        GROUP BY id_categoria
        ORDER BY total_productos DESC
        LIMIT 6";
        $objeto = [];
        $Categorias = (new ConsultaGlobal())->ConsultaGlobal($Consulta);
        foreach ($Categorias as $value) {
            $datos = [
                "id" => $value->id_categoria,
                "type" => "shop",
                "name" =>  $value->glosa_categoria,
                "slug" => $value->urlamigable_categoria,
                "path" => "shop/catalog",
                "image" => "assets/images/categories/category-1.jpg",
                "items" => $value->total_productos,
                "customFields" => [],
                "parents" => null,
                "children" => []
            ];
            array_push($objeto,$datos);
        }
        echo json_encode($objeto);
    }
}
