<?php

use App\Helpers\Helper;
?>
<html lang="en">

<head>
    <title><?php
            echo $informacion_empresa['tipo_documento']
            ?></title>

    <style>
        .center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .no-border {
            border: none;
        }

        .percent-5 {
            width: 5%;
        }

        .percent-10 {
            width: 10%;
        }

        .percent-15 {
            width: 15%;
        }

        .percent-20 {
            width: 20%;
        }

        .percent-25 {
            width: 25%;
            font-size: 12px;
        }

        .percent-30 {
            width: 30%;
        }

        .percent-40 {
            width: 40%;
        }

        .percent-50 {
            width: 50%;
            /* font-size: 12px; */
        }

        .percent-60 {
            width: 60%;
        }

        .percent-70 {
            width: 70%;
        }

        .percent-80 {
            width: 80%;
        }

        .percent-90 {
            width: 90%;
        }

        .percent-100 {
            width: 100%;
        }

        .font-size-12 {
            font-size: 12px;
        }

        .font-size-15 {
            font-size: 13px;
        }

        .miTabla {

            border-left: 0.01em solid #000;
            border-right: 0;
            border-top: 0.01em solid #000;
            border-bottom: 0;
            border-collapse: collapse;
        }

        .miTabla td,
        .miTabla th {

            padding-left: 5px;
            padding-right: 5px;
            border-left: 0;
            border-right: 0.01em solid #000;
            border-top: 0;
            border-bottom: 0.01em solid #000;
        }

        .header-table {
            backgnumber_format: #EDEDED;
            /* color:#fff; */
        }

        .header-table-black {
            backgnumber_format: #000;
            color: #fff;
        }

        .align-right {
            text-align: right;
        }

        .list-no-style {
            list-style: none;
        }

        .text-primary {
            color: #5a8dee;
        }

        .border {
            border: 1px solid #000;
        }



        .border-red {
            border: 2px solid red;
        }

        .padding-left-5 {
            padding: 5px;
        }

        .text-danger {
            color: red;
        }

        .font-size-16 {
            font-size: 16px;
        }

        .no-visibility {
            visibility: hidden !important;
        }
    </style>
    <script>

    </script>
</head>

<body>
    <table class=" percent-100">
        <tr>
            <td class="percent-60">
                <table class=" percent-100">
                    <tr>
                        <td class="percent-20 ">
                            <img style="width:180px; height:auto;" src="data:image/png;base64,<?= $imagen ?>" alt="" />
                        </td>
                        <td class="percent-80  ">
                            <ul class="list-no-style padding-left-5">
                                <li class="text-primary" style="font-family: sans-serif;"><b><?= $informacion_empresa['nombre_empresa'] ?></b></li>
                                <!-- <li class="font-size-12" style="font-family: sans-serif;" style="font-family: sans-serif;">Sucursal: {{$informacion['emisor']['sucursal']}}</li> -->
                                <li class="font-size-12" style="font-family: sans-serif;"><?= $informacion_empresa['razonSocial'] ?></li>
                                <li class="font-size-12" style="font-family: sans-serif;"><?= $informacion_empresa['direccion'] ?></li>
                                <li class="font-size-12" style="font-family: sans-serif;"><?= $informacion_empresa['departamento'] ?>-<?= $informacion_empresa['provincia'] ?>-<?= $informacion_empresa['distrito'] ?></li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align:center; font-size:25px;" class="percent-40  ">
                <div class="border-red">
                    <span class="text-danger font-size-16" style="font-family: sans-serif;"><b>R.U.C.: <?= $informacion_empresa['ruc'] ?></b></span>
                    <br>
                    <span class="text-danger font-size-16" style="font-family: sans-serif;"><b><?= $informacion_empresa['tipo_documento'] ?> ELECTRONICA</b></span>
                    <br>
                    <span class="text-danger font-size-16" style="font-family: sans-serif;"><b>N° <?= $correlativo ?></b></span>
                    <br>
                </div>
            </td>
        </tr>
    </table>

    <table class="percent-100">
        <tr>
            <td class="percent-100 align-right">
                <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;">Fecha Emisión: <?php
                                                                                                            $fechas = explode("-", $fecha_emision_dte);
                                                                                                            echo  $fechas[2]." de " . helpers::nombreMes($fecha_emision_dte) . " del " .  $fechas[0] ?></p>
            </td>
        </tr>
        <tr>
            <td class="percent-100 border">
                <table class=" percent-100">
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"> <b>SEÑOR(ES):</b> <?= $informacion_cliente['nombre_cliente_completo'] ?></td>
                        <td class="percent-50 font-size-12 " style="font-family: sans-serif;"> <b>VENDEDOR:</b> <?= $informacion_documento['vendedor_documento'] ?> </td>
                    </tr>
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"> <b>
                                <?= ($informacion_empresa['tipo_documento'] == 'BOLETA') ? 'DNI:'  : 'R.U.C.:' ?>
                            </b><?= ($informacion_empresa['tipo_documento'] == 'BOLETA') ? $informacion_cliente['dni_cliente']  : $informacion_cliente['ruc_cliente'] ?></td>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"><b>EMAIL:</b> </td>
                    </tr>
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"> <b>GIRO:</b></td>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"><b>TELÉFONO:</b></td>
                    </tr>
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"><b> DIRECCIÓN:</b></td>
                    </tr>
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"><b>COMUNA:</b></td>
                    </tr>
                    <tr>
                        <td class="percent-50 font-size-12" style="font-family: sans-serif;"><b>REGIÓN:</b></td>
                    </tr>
                </table>
            </td>
        </tr>
        </tr>
    </table>
    <br>
    <span class="font-size-12"><b style="font-family: sans-serif;">Referencias:</b></span>
    <table class="percent-100 miTabla">
        <thead class="header-table">
            <tr>
                <td class="percent-5 center font-size-12" style="font-family: sans-serif">
                    <span><b>COD.</b></span>
                </td>
                <td class="percent-30 center font-size-12" style="font-family: sans-serif">
                    <span><b>DESCRIPCIÓN</b></span>
                </td>
                <td class="percent-10 center font-size-12" style="font-family: sans-serif">
                    <span><b>CANTIDAD</b></span>
                </td>
                <td class="percent-10 center font-size-12" style="font-family: sans-serif">
                    <span><b>U.M</b></span>
                </td>
                <td class="percent-10 center font-size-12" style="font-family: sans-serif">
                    <span><b>P. UNIT.</b></span>
                </td>
                <td class="percent-10 center font-size-12" style="font-family: sans-serif">
                    <span><b>DSCTO</b></span>
                </td>
                <td class="percent-10 center font-size-12" style="font-family: sans-serif">
                    <span><b>SUBTOTAL</b></span>
                </td>
            </tr>
        </thead>
        <tbody class="percent-100">
            <?php foreach ($negocios as $key => $elemento) {
                $borde = "border-top:1px solid white; ";
                if ($key == 0) {
                    $borde = "";
                }
            ?>
                <tr class="percent-100" style="padding: 0px;margin: 0px">
                    <td class="percent-5 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;"><?= $elemento['codigo_producto'] ?></p>
                    </td>
                    <td class="percent-30 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;"><?= $elemento['glosa_producto'] ?></p>
                    </td>
                    <td class="percent-10 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p><?= $elemento['cantidad_negocio_detalle'] ?></p>
                    </td>
                    <td class="percent-10 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;"></p>
                    </td>
                    <td class="percent-10 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;">S/<?= number_format($elemento['preciounitario_negocio_detalle'],2) ?></p>
                    </td>
                    <td class="percent-10 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;"><?= $elemento['descuentogeneral_negocio_detalle'] ?> %</p>
                    </td>
                    <td class="percent-10 center font-size-12" style="padding: 0px;margin: 0px; <?= $borde ?>">
                        <p style="font-size: 12px;padding: 0px;margin: 0px;font-family: sans-serif;">S/<?= number_format($elemento['total_negocio_detalle'],2) ?></p>
                    </td>
                </tr>
                <?php if ($key == count($negocios) - 1) {
                    if (count($negocios) < 10) {
                        for ($i = 0; $i < 10 - count($negocios); $i++) {
                            $borde = "border-top:1px solid white; "; ?>
                            <tr class="percent-100">
                                <td class="percent-5 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                                <td class="percent-30 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                                <td class="percent-10 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility">.</p>
                                </td>
                                <td class="percent-10 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                                <td class="percent-10 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                                <td class="percent-10 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                                <td class="percent-10 center font-size-12" style="<?= $borde ?>">
                                    <p class="no-visibility" style="font-size: 12px;padding: 0px;margin: 0px;">.</p>
                                </td>
                            </tr>
            <?php
                        }
                    }
                }
            }
            ?>
        </tbody>
    </table>
    <table class=" percent-100">
        <tr>
            <td class="percent-50">

            </td>

            <td class="percent-50">
                <table class="miTabla percent-100">
                    <tr>
                        <td class="font-size-12" style="font-family: sans-serif;">MONTO NETO</td>
                        <td>
                            <p style="text-align:right;padding: 0px;margin: 0px;font-size: 12px;font-family: sans-serif">S/<?=number_format($total_afecto,2)?> </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-size-12" style="font-family: sans-serif;">IGV 19%</td>
                        <td>
                            <p style="text-align:right;padding: 0px;margin: 0px;font-size: 12px;font-family: sans-serif">S/<?=number_format($igv_total,2)?> </p>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-size-12" style="font-family: sans-serif;">TOTAL</td>
                        <td>
                            <p style="text-align:right;padding: 0px;margin: 0px;font-size: 12px;font-family: sans-serif">S/<?= number_format($importe_total,2)?> </p>
                        </td>
                    </tr>
                </table>
            </td>
        <tr>

    </table>

    <table class="percent-100 miTabla">
        <tr class="percent-100">
            <td>
                <p style="text-align:start;padding: 0px;margin: 0px;font-size: 12px;font-family: sans-serif">Observaciones</p>
            </td>
        </tr>
    </table>
    <br>
    <table class="percent-100">
        <tr class="percent-100">
            <td class="percent-40">
                <img style="width:50%;height:100px;" src="data:image/png;base64,<?=$codigoBarra?>" alt="" />
            </td>
            <td class="percent-50">

            </td>
        </tr>
    </table>
</body>

</html>