<?php

require_once "models/ConsultaGlobal.php";

/**
 * [CORREO] Plantilla HTML reutilizable y con estilo para todos los correos del sistema.
 * Usa tablas + CSS inline (lo que mejor renderizan Gmail/Outlook/Hotmail). Toma los
 * datos de la empresa (nombre, logo, RUC, contacto) para personalizar header y footer.
 *
 *   EmailTemplate::comprobante('BOLETA');           // correo de comprobante
 *   EmailTemplate::render($titulo, $introHtml, $detalles, $notaFinal); // genérico
 */
class EmailTemplate
{
    private static $empresa = null;
    private static $resuelta = false;
    // Color institucional del sistema (mismo azul del panel, botones y reportes).
    private static $color = '#1976d2';

    private static function empresa()
    {
        if (self::$resuelta) {
            return self::$empresa;
        }
        self::$resuelta = true;
        try {
            $rows = (new ConsultaGlobal())->ConsultaGlobal(
                "SELECT nombre_empresa_venta_online, ruc_empresa_venta_online,
                        telefono_empresa_venta_online, celular_empresa_venta_online,
                        direccion_empresa_venta_online, urllogohorizontal_empresa_venta_online
                 FROM empresa_venta_online LIMIT 1"
            );
            self::$empresa = isset($rows[0]) ? $rows[0] : null;
        } catch (\Throwable $e) {
            self::$empresa = null;
        }
        return self::$empresa;
    }

    /**
     * Envuelve el contenido en la plantilla branded.
     * @param string $titulo      Título principal (texto plano).
     * @param string $introHtml   Párrafo introductorio (puede traer HTML simple, p.ej. <strong>).
     * @param array  $detalles    Pares etiqueta => valor que se muestran en una tabla.
     * @param string $notaFinal   Texto final opcional.
     */
    public static function render($titulo, $introHtml, $detalles = [], $notaFinal = '')
    {
        $emp = self::empresa();
        $nombre = ($emp && $emp->nombre_empresa_venta_online) ? htmlspecialchars($emp->nombre_empresa_venta_online) : 'Botica';
        $ruc = $emp ? htmlspecialchars((string) $emp->ruc_empresa_venta_online) : '';
        $tel = $emp ? trim(((string) ($emp->telefono_empresa_venta_online ?: '')) . ' ' . ((string) ($emp->celular_empresa_venta_online ?: ''))) : '';
        $dir = $emp ? htmlspecialchars((string) $emp->direccion_empresa_venta_online) : '';
        $logo = ($emp && $emp->urllogohorizontal_empresa_venta_online) ? $emp->urllogohorizontal_empresa_venta_online : '';

        $color = self::$color;
        $headerBg = $logo ? '#ffffff' : $color;
        $headerInner = $logo
            ? '<img src="' . htmlspecialchars($logo) . '" alt="' . $nombre . '" style="max-height:60px;max-width:230px;">'
            : '<span style="font-size:22px;font-weight:bold;color:#ffffff;letter-spacing:.5px;">' . $nombre . '</span>';

        $filas = '';
        foreach ($detalles as $k => $v) {
            $filas .= '<tr>
                <td style="padding:8px 0;color:#6b7280;font-size:13px;">' . htmlspecialchars($k) . '</td>
                <td style="padding:8px 0;color:#111827;font-size:13px;font-weight:600;text-align:right;">' . htmlspecialchars((string) $v) . '</td>
            </tr>';
        }
        $bloqueDetalles = $filas
            ? '<table width="100%" style="border-collapse:collapse;margin:18px 0;border-top:1px solid #eef0f2;border-bottom:1px solid #eef0f2;">' . $filas . '</table>'
            : '';

        $contacto = [];
        if ($ruc) { $contacto[] = 'RUC: ' . $ruc; }
        if ($tel) { $contacto[] = htmlspecialchars($tel); }
        if ($dir) { $contacto[] = $dir; }
        $contactoHtml = $contacto ? implode(' &nbsp;&bull;&nbsp; ', $contacto) : '';

        return '
<div style="background:#f4f6f8;padding:24px 12px;font-family:Arial,Helvetica,sans-serif;">
  <table align="center" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.07);border-collapse:collapse;margin:0 auto;">
    <tr><td style="background:' . $headerBg . ';padding:24px;text-align:center;">' . $headerInner . '</td></tr>
    <tr><td style="height:4px;background:' . $color . ';font-size:0;line-height:0;">&nbsp;</td></tr>
    <tr><td style="padding:30px 34px 8px;">
        <h1 style="margin:0 0 8px;font-size:20px;color:' . $color . ';">' . htmlspecialchars($titulo) . '</h1>
        <p style="margin:0 0 4px;font-size:14px;line-height:1.65;color:#4b5563;">' . $introHtml . '</p>
        ' . $bloqueDetalles . '
        ' . ($notaFinal ? '<p style="margin:6px 0 0;font-size:14px;line-height:1.65;color:#4b5563;">' . $notaFinal . '</p>' : '') . '
        <p style="margin:22px 0 30px;font-size:15px;color:' . $color . ';font-weight:600;">¡Gracias por su preferencia! 🌿</p>
    </td></tr>
    <tr><td style="background:#f9fafb;padding:18px 34px;border-top:1px solid #eef0f2;text-align:center;">
        <p style="margin:0 0 4px;font-size:13px;font-weight:bold;color:' . $color . ';">' . $nombre . '</p>
        ' . ($contactoHtml ? '<p style="margin:0;font-size:12px;color:#6b7280;line-height:1.5;">' . $contactoHtml . '</p>' : '') . '
    </td></tr>
  </table>
  <p style="text-align:center;color:#9ca3af;font-size:11px;margin:14px 0 0;font-family:Arial,Helvetica,sans-serif;">Este es un correo automático, por favor no responda a este mensaje.</p>
</div>';
    }

    /** Correo de comprobante (BOLETA / FACTURA / NOTA VENTA / TICKET). El PDF va adjunto. */
    public static function comprobante($tipoDocumento, $numero = '')
    {
        $tipo = ucwords(strtolower(trim((string) $tipoDocumento)));
        if ($tipo === '') { $tipo = 'Comprobante'; }
        $intro = 'Hemos adjuntado tu <strong>' . htmlspecialchars($tipo) . '</strong> en formato PDF a este correo. '
            . 'Puedes <strong>descargarlo, imprimirlo o guardarlo</strong> cuando lo necesites.';
        $detalles = ['Comprobante' => $tipo];
        if ($numero !== '' && $numero !== null) { $detalles['N° documento'] = $numero; }
        $detalles['Fecha de emisión'] = date('d/m/Y H:i');
        $nota = 'Conserva este documento como respaldo de tu compra. Ante cualquier consulta, no dudes en contactarnos.';
        return self::render('Tu comprobante está listo ✅', $intro, $detalles, $nota);
    }
}
