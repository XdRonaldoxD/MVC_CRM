<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Punto de Venta</title>

    <style>
        html {

            font-family: Arial, Helvetica, sans-serif;
        }

        .w-100 {
            width: 100%;
        }

        .w-80 {
            width: 80%;
        }

        .w-70 {
            width: 70%;
        }

        .w-60 {
            width: 60%;
        }

        .w-50 {
            width: 50%;
        }

        .w-40 {
            width: 40%;
        }

        .w-30 {
            width: 30%;
        }

        .w-20 {
            width: 20%;
        }

        .w-15 {
            width: 15%;
        }

        .w-10 {
            width: 10%;
        }

        .p-1 {
            padding: 8px;
        }

        .p-2 {
            padding: 16px;
        }

        p {
            margin: 0;
            font-size: 9px;
        }

        .m-0 {
            margin: 0;
        }

        .fw-bold {
            font-weight: bold;
        }

        .mb {
            margin-bottom: 5px;
        }

        .fs-small {
            font-size: 11px;
        }

        .fs-large {
            font-size: 14px;
        }

        .fs-xlarge {
            font-size: 16px;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        hr {
            height: 0px;
            border-bottom: 0px;
            border-top: double 2px black;
            margin: 5px 0px;
        }





        .titulocabezera {
            background-color: rgba(0, 0, 0, 0.05);

        }

        img {
            position: absolute;
            width: 80px;
            height: 80px;
            margin: 10px;
        }

        .size {
            width: 100%;
            height: 365px;
        }

        html {
            margin: 10px 15px
        }



        .t-desglose td:last-child,
        .t-desglose th:last-child {

            border-right: 0.01em solid #ff000000;

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

        table {
            table-layout: fixed;
            width: 100%;
        }
    </style>
</head>

<body>
    <div style="text-align: center">
        <strong style="font-size: 15px;margin: 0px;"><?= $informacion_empresa['nombre_empresa'] ?></strong>
    </div>
    <div style="text-align: center;">
        <p style="font-size: 9px;margin: 0px;">R.U.C.: <?= $informacion_empresa['ruc'] ?></p>
    </div>
    <div style="text-align: center;">
        <P style="font-size: 9px;margin: 0px;">DE:<?= $informacion_empresa['razonSocial'] ?></P>
    </div>
    <div style="text-align: center;">
        <p style="font-size: 9px;margin: 0px;"><?= $informacion_empresa['direccion'] ?></p>
    </div>
    <div style="text-align: center;">
        <p style="font-size: 9px;margin: 0px;"><?= $informacion_empresa['departamento'] ?>-<?= $informacion_empresa['provincia'] ?>-<?= $informacion_empresa['distrito'] ?></p>
    </div>
    <hr>
    <div style="text-align: center;">
        <strong style="font-size: 0.8rem;margin: 0px;padding: 0px;"><?php
                                                                    if ($informacion_empresa['tipo_documento'] == "NOTA_VENTA") {
                                                                        echo "NOTA VENTA";
                                                                    } else {
                                                                        echo $informacion_empresa['tipo_documento'] . ' ELECTRONICA';
                                                                    }
                                                                    ?>
        </strong>
    </div>
    <div style="text-align: center;">
        <strong style="font-size: 0.8rem;margin: 0px;padding: 0px;"><?php
                                                                    if ($informacion_documento['serie']) {
                                                                        echo $informacion_documento['serie'] . '-';
                                                                    } else {
                                                                        echo 'N° ';
                                                                    }
                                                                    ?>
            <?= $informacion_documento['correlativo'] ?> </strong>
    </div>
    <hr>
    <div style="text-align: start;">
        <p style="font-size: 9px;margin: 0px;">CLIENTE:<?= $informacion_cliente['nombre_cliente_completo'] ?></p>
    </div>

    <div style="text-align: start;">
        <?php
        if ($informacion_empresa['tipo_documento'] === "BOLETA") {
        ?>
            <p style="font-size: 9px;margin: 0px;">DNI:<?= $informacion_cliente['dni_cliente'] ?></p>
        <?php
        } elseif ($informacion_empresa['tipo_documento'] === "FACTURA") {
        ?>
            <p style="font-size: 9px;margin: 0px;">RUC:<?= $informacion_cliente['ruc_cliente'] ?></p>
        <?php
        }
        ?>
    </div>
    <div style="text-align: start;">
        <p style="font-size: 9px;margin: 0px;">DIRECCIÓN:<?= $informacion_cliente['direccion_cliente'] ?></p>
    </div>
    <div style="text-align: start;">
        <p style="font-size: 9px;margin: 0px;">VENDEDOR:<?= $informacion_documento['vendedor_documento'] ?></p>
    </div>
    <hr>
    <table style="text-align:center;" class="t-desglose miTabla fs-small w-100">
        <thead>
            <tr>
                <th class="w-20" style="padding: 0px;">
                    <p> CODIGO </p>
                </th>
                <th class="w-30" style="padding: 0px;">
                    <p> DESCRIPCION
                    </p>
                </th>

                <th class="w-10" style="padding: 0px;">
                    <p> CANT.
                    </p>
                </th>
                <th class="w-15" style="padding: 0px;">
                    <p> VALOR</p>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($data['details'] as $ventas) {
            ?>
                <tr>
                    <td style="padding: 0px;">
                        <p><?= $ventas['codigo'] ?></p>
                    </td>
                    <td style="padding: 0px;">
                        <p><?= $ventas['descripcion'] ?> </p>
                    </td>
                    <td style="padding: 0px;">
                        <p><?= $ventas['cantidad'] ?></p>
                    </td>
                    <td style="padding: 0px;">
                        <p>S/.<?= round($ventas['valor_total'], 2) ?></p>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="font-size: 9px;padding:0px"> <strong>TOTAL</strong> S/<?= round($total_afecto, 2) ?> </td>
            </tr>
        </tfoot>

    </table>

    <br>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p><strong> TOTAL </strong></p>

            </td>
            <td class="w-50">
                <p> S/<?= round($valorventa, 2) ?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p>EFECTIVO</p>

            </td>
            <td class="w-50">
                <p> S/<?= round($pagocliente_venta, 2) ?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p> VUELTO</p>

            </td>
            <td class="w-50">
                <p> S/<?= round($vuelto_negocio, 2) ?></p>
            </td>
        </tr>
    </table>


    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px">FECHA Y HORA DE VENTA:</p>

            </td>
            <td class="w-50">
                <p style="font-size: 9px;padding:0px"><?= date('d/m/Y', strtotime($fecha_creacion_venta)) ?> <?= date('g:i A', strtotime($fecha_creacion_venta)) ?></p>
            </td>

        </tr>
    </table>
    <table class="w-100 fs-small">
        <tr>
            <td class="w-100">
                <p style="font-size: 9px;padding:0px">TOTAL NUM. ITEMS:<?= count($data['details']) ?></p>
            </td>
        </tr>
    </table>

    <table class="w-100 fs-small">
        <tr>
            <td class="w-50">
                <p>TOTAL AFECTO</p>

            </td>
            <td class="w-50">
                <p> S/<?= round($total_afecto, 2) ?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p>IGV</p>

            </td>
            <td class="w-50">
                <p> S/<?= round($igv_total, 2) ?></p>
            </td>
        </tr>
        <tr>
            <td class="w-50">
                <p>IMPORTE TOTAL</p>

            </td>
            <td class="w-50">
                <p> S/<?= round($importe_total, 2) ?></p>
            </td>
        </tr>
    </table>
    <div style="text-align: center;">
        <b style="font-family: 'Constantia', serif;">!GRACIAS POR SU COMPRA!</b>
    </div>
    <div style="width: 100%">
        <img style="margin-left: 80px;" src="data:image/png;base64,<?= $codigoBarra ?>" alt="" />
    </div>
</body>
</html>