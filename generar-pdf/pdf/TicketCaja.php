<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            margin: 2px;
            margin-right: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin: 0px;
            padding: 0px;
            table-layout: fixed;
            background-color: transparent;
            border-collapse: collapse;
        }

        .w-10 {
            width: 10%;
        }

        .w-15 {
            width: 15%;
        }

        .w-20 {
            width: 20%;
        }

        .w-25 {
            width: 25%;
        }

        .w-30 {
            width: 30%;
        }

        .w-35 {
            width: 35%;
        }

        .w-40 {
            width: 40%;
        }

        .w-45 {
            width: 45%;
        }

        .w-50 {
            width: 50%;
        }

        .w-55 {
            width: 55%;
        }

        .w-60 {
            width: 60%;
        }

        .w-65 {
            width: 65%;
        }

        .w-70 {
            width: 70%;
        }

        .w-75 {
            width: 75%;
        }

        .w-80 {
            width: 80%;
        }

        .w-85 {
            width: 85%;
        }

        .w-90 {
            width: 90%;
        }

        .w-95 {
            width: 95%;
        }

        .w-100 {
            width: 100%;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .mx-auto {
            margin: 0 auto;
        }

        .m-0 {
            margin: 0;
        }

        .p-0 {
            padding: 0;
        }

        .mt-1 {
            margin-top: 1rem;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .mb-1 {
            margin-bottom: 1rem;
        }

        .font-weight-bold {
            font-weight: bold;
        }

        .text-transform-uppercase {
            text-transform: uppercase;
        }

        .font-size-8 {
            font-size: 12px;
        }

        .font-size-10 {
            font-size: 12px;
        }

        .font-size-12 {
            font-size: 12px;
        }

        .font-size-14 {
            font-size: 14px;
        }

        .font-size-16 {
            font-size: 16px;
        }

        .font-size-18 {
            font-size: 18px;
        }

        .font-size-20 {
            font-size: 20px;
        }

        .font-size-24 {
            font-size: 24px;
        }

        .hr-gray {
            border: 1px solid #ccc;
        }

        .tabla-detalle {
            border-collapse: collapse;
        }

        .tabla-detalle {
            border: 2px solid black;
        }

        .tabla-detalle th {
            border: 1px solid black;
        }

        .tabla-detalle td {
            border: 1px solid black;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .numero_nv_content {
            position: relative;
            width: 100%;
            height: 200px;
        }

        .numero_nv {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }



        .tabla_numero_nota {
            margin-bottom: 5px;
        }

        .tabla_numero_nota td {
            padding: 5px;
            border-top: 1px solid #000000;
            border-bottom: 1px solid #000000;
        }

        .tabla_cliente {
            font-size: 10px;
            margin-bottom: 20px;
        }

        .tabla_cliente tr td:first-child {
            margin-left: 7px;
        }

        .tabla_productos {
            font-size: 10px;
            margin-bottom: 10px;
        }

        .tabla_productos th {
            text-align: center;
            border-top: solid 1px #000000;
            border-bottom: solid 1px #000000;
            background-color: #f3f3f3;
        }

        .tabla_productos td {
            text-align: center;
            border-bottom: solid 1px #000000;
        }

        .tabla_pagos {
            font-size: 10px;
            margin-left: 7px;
        }

        .tabla_totales {
            font-size: 10px;
            margin: 0px 10px;
        }

        .tabla_totales thead th {
            padding: 10px 0px;
        }

        .tabla_totales tbody td {
            padding: 5px 0px;
        }

        .tabla_totales tfoot td {
            padding: 10px 0px;
        }
    </style>
</head>

<body>
    <table class="table tabla_encabezado">
        <tr>
            <td align="center" valign="middle" style="padding: 10px;">
                <img style="width:120px; height:auto; margin:0px 0px 0px 70px" src="data:image/png;base64,<?= $imagen ?>" alt="" />
            </td>
        </tr>
    </table>

    <table class="table tabla_cliente">
        <tr>
            <td valign="top" align="right" class="w-40"><strong>Vendedor:</strong></td>
            <td class="text-uppercase w-60"><?= $caja->nombre_staff . ' ' . $caja->apellidopaterno_staff . ' ' . $caja->apellidomaterno_staff ?></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="w-40"><strong>DNI:</strong></td>
            <td class="text-uppercase w-60"><?= $caja->rut_staff ?></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="w-40"><strong>Fecha y Hora:</strong></td>
            <td class="text-uppercase w-60"><?php echo date('Y/m/d H:i', strtotime($caja->fechacreacion_caja)) ?></td>
        </tr>
        <tr>
            <td valign="top" align="right" class="w-40"><strong>Nro de Caja:</strong></td>
            <td class="text-uppercase w-60"><?= $caja->id_caja ?></td>
        </tr>
    </table>

    <table class="table tabla_numero_nota">
        <tr>
            <td align="center" valign="middle">
                <p class="font-size-10 font-weight-bold">RESUMEN DE CAJA</p>
            </td>
        </tr>
    </table>
    <table class="table tabla_totales">
        <thead>
            <tr>
                <th class="text-transform-uppercase">Medio de Pago</th>
                <th class="text-transform-uppercase" align="right">Monto</th>
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
                <td width="60%" class="font-weight-bold">TOTAL EN CAJA</td>
                <td class="font-weight-bold" width="40%" align="right">S/ <?php echo number_format($respuesta['total_caja'], 2) ?></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>