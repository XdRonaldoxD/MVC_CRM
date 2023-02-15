<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Resumen Caja</title>
    <style>
        * {
            font-family: sans-serif;
        }

        html {
            margin: 10px;
        }

        .resumen__head {
            height: 40px;
            padding: 0px 30px;
        }

        .resumen__head .encabezado {
            height: 40px;
            float: right;
            font-size: 15px;
        }

        .resumen__head .encabezado p {
            margin: 0;
            line-height: 50px;
        }

        .resumen__body {
            padding: 20px 30px;
        }

        .resumen__body .tittle {
            height: 40px;
            text-align: center;
            margin: 20px 0 40px 0;
        }

        .resumen__body h1 {
            margin: 0;
            line-height: 50px;
        }

        .montos__tabla {
            width: 100%;
        }

        .datos {
            font-size: 13px;
            padding: 5px;
        }

        #tableta {
            font-size: 15px;
        }

        #tabla_contenido {
            font-size: 15px;
        }

        #td_tabla {
            padding: 5px 15px;
        }

        #tabla_td {
            padding: 5px 3px;
        }

        .table-datatable {
            width: 100%;
            border-collapse: collapse;
        }

        .table-datatable td,
        .table-datatable th {
            border: solid 1px #3d3d3d;
            padding: 5px 0px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="resumen">
        <div class="resumen__head">
            <table style="width:100%;">
                <tr>
                    <td style="width:50%;">
                        <img style="width:120px; height:auto; margin:0px 0px 0px 70px" src="data:image/png;base64,<?= $imagen ?>" alt="" />
                    </td>
                    <td style="width:50%;">
                        <div class="encabezado">
                            <p>Fecha de emisión: <?php echo date('Y/m/d H:i') ?></p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="resumen__body">
            <div class="tittle">
                <h1>RESUMEN DE CAJA DIARIA</h1>
            </div>

            <table id="tabla_contenido" style="width:100%;">
                <tr>
                    <td style="width:50%;" id="tabla_td">
                        <table style="width:100%;">
                            <tr>
                                <td id="tabla_td">
                                    <strong>Nombre:</strong> <?= $caja->nombre_staff . ' ' . $caja->apellidopaterno_staff . ' ' . $caja->apellidomaterno_staff ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tabla_td">
                                    <strong>DNI:</strong><?= $caja->rut_staff ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tabla_td">
                                    <strong>Fecha de Caja:</strong><?php echo date('Y/m/d H:i', strtotime($caja->fechacreacion_caja)) ?>
                                </td>
                            </tr>
                        </table>
                    <td>
                    <td style="width:50%;" id="tabla_td">
                        <table style="width:100%;">
                            <tr>
                                <td id="tabla_td">
                                    <strong>Apertura:</strong><?php echo date('H:i A', strtotime($caja->fechacreacion_caja)) ?>
                                </td>
                            </tr>
                            <tr>
                                <td id="tabla_td">
                                    <strong>Cierre:</strong><?php echo date('H:i A', strtotime($caja->fechacierre_caja)) ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <br>
            <table width="100%">
                <tr>
                    <td width="100%" style="padding: 15px;" valign="top">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th colspan="2" align="left" class="datos" style="font-size: 18px;">Resumen Caja:</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($respuesta['data_no_efectivo'] as $item) {  ?>
                                    <tr>
                                        <td class="datos" width="60%"><?= $item['glosa_pago'] ?> <?php echo $item['cantidad'] ?>:</td>
                                        <td class="datos" width="40%"><?= '+' ?>S/<?php echo  number_format($item['valor_pago'], 2) ?></td>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td class="datos" width="60%"> <strong> Total Efectivo :</strong></td>
                                    <td class="datos" width="40%">+S/<?php echo number_format($respuesta['total_efectivo'], 2) ?></td>
                                </tr>
                                <tr>
                                    <td class="datos" width="60%">Saldo de Apertura:</td>
                                    <td class="datos" width="40%">+S/<?php echo number_format($respuesta['montoinicial_caja'], 2) ?></td>
                                </tr>
                                <?php foreach ($respuesta['data_efectivo'] as $item) {
                                    if ($item['documento'] === "INGRESO") {
                                        $signo = '+';
                                    } else {
                                        $signo = '-';
                                    }
                                ?>
                                    <tr>
                                        <td class="datos" width="60%"><?= $item['glosa_pago'] ?> <?php echo $item['cantidad'] ?>:</td>
                                        <td class="datos" width="40%"><?= $signo ?>S/<?php echo number_format($item['valor_pago'], 2) ?></td>
                                    </tr>
                                <?php } ?>


                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">
                                        <hr>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="datos" style="padding: 10px 5px; font-size: 18px;"><strong>Total en caja :</strong></td>
                                    <td class="datos" style="padding: 10px 5px; font-size: 18px;"><strong>S/ <?php echo number_format($respuesta['total_caja'], 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>