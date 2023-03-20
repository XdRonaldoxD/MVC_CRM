<?php


require_once "models/EmpresaVentaOnline.php";
require_once "models/CertificadoDigital.php";
require_once "models/ConsultaGlobal.php";
class EmpresaController
{

    public function GuardarInformacion()
    {
        $informacionForm = json_decode($_POST['informacionForm']);
        $fillable = [
            'ruc_empresa_venta_online' => $informacionForm->ruc_empresa,
            'razon_social_empresa_venta_online' => $informacionForm->razon_social_empresa,
            'telefono_empresa_venta_online' => $informacionForm->telefono_empresa,
            'celular_empresa_venta_online' => $informacionForm->celular_empresa,
            'direccion_empresa_venta_online' => $informacionForm->direccion_empresa,
            'idDistrito' => empty($informacionForm->distrito) ? null : $informacionForm->distrito,
            'dominio_empresa_venta_online' => $_SERVER['SERVER_NAME'],
            'pixelgoogle_empresa_venta_online' => $informacionForm->pixelgoogle_empresa,
            'pixelfacebook_empresa_venta_online' => $informacionForm->pixelfacebook_empresa,
        ];
        if (isset($_FILES['pathfoto_empresa_venta_online'])) {
            $imagen = $_FILES['pathfoto_empresa_venta_online']['name'];
            $ext = pathinfo($imagen, PATHINFO_EXTENSION);
            $nombre_imagen = pathinfo($imagen, PATHINFO_FILENAME);
            $nombre_imagen = preg_replace('([^A-Za-z0-9])', '', $nombre_imagen);
            $temp = $_FILES['pathfoto_empresa_venta_online']['tmp_name'];
            //crear el directorio
            if (!file_exists(__DIR__ . "/../archivo/imagenes_empresa")) {
                mkdir(__DIR__ . "/../archivo/imagenes_empresa", 0777, true);
            }
            $EmpresaVentaOnline = EmpresaVentaOnline::where('pathfoto_empresa_venta_online', $informacionForm->pathfoto_empresa_venta_online)->first();
            if (isset($EmpresaVentaOnline) && $EmpresaVentaOnline->pathfoto_empresa_venta_online) {
                if (file_exists(__DIR__ . "/../archivo/imagenes_empresa/$EmpresaVentaOnline->pathfoto_empresa_venta_online")) {
                    unlink(__DIR__ . "/../archivo/imagenes_empresa/$EmpresaVentaOnline->pathfoto_empresa_venta_online");
                }
            }
            $path = time() . $nombre_imagen;
            $ruta_archivo = __DIR__ . "/../archivo/imagenes_empresa/$path.'.'.$ext";
            move_uploaded_file($temp, $ruta_archivo);  // GUARDA LA archivo_digital
            $fillable += [
                'pathfoto_empresa_venta_online' => date("Y-m-d H:i:s", "$path.'.'.$ext")
            ];
        }

        if (isset($_FILES['archivo_digital'])) {
            $imagen = $_FILES['archivo_digital']['name'];
            $ext = pathinfo($imagen, PATHINFO_EXTENSION);
            $nombre_certificado = pathinfo($imagen, PATHINFO_FILENAME);
            $nombre_certificado = preg_replace('([^A-Za-z0-9])', '', $nombre_certificado);
            $temp = $_FILES['archivo_digital']['tmp_name'];
            //crear el directorio
            if (!file_exists(__DIR__ . "/../archivo/certificado_digital")) {
                mkdir(__DIR__ . "/../archivo/certificado_digital", 0777, true);
            }
            $path = $nombre_certificado;
            $ruta_archivo = __DIR__ . "/../archivo/certificado_digital/$path.$ext";
            move_uploaded_file($temp, $ruta_archivo);  // GUARDA LA archivo_digital
            $cert_path = $ruta_archivo;
            // Contraseña del certificado
            $cert_password = $informacionForm->clave_archivo;
            // Carga el certificado y la clave privada en el contexto de OpenSSL
            $context = openssl_pkcs12_read(file_get_contents($cert_path), $certs, $cert_password);
            if ($context) {
                // Obtén la fecha de inicio del certificado
                $cert_data = openssl_x509_parse($certs['cert']);
                // Obtén la fecha de finalización del certificado
                $start_date = date('Y-m-d H:i:s', $cert_data['validFrom_time_t']);
                // Obtén la fecha de finalización del certificado
                $end_date = date('Y-m-d H:i:s', $cert_data['validTo_time_t']);
                $nombre_certificado_digital = $cert_data['name'];
                preg_match('/CN=(.*?)\s/', $nombre_certificado_digital, $matches);
                $nombre_certificado_digital = $matches[1];
                $CertificadoDigital = [
                    'fechainicio_certificado_digital' => $start_date,
                    'fechafin_certificado_digital' => $end_date,
                    'path_certificado_digital' => $path . '.' . $ext,
                    "fechacreacion_certificado_digital" => date('Y-m-d H:i:s'),
                    'nombre_certificado_digital' => $nombre_certificado_digital
                ];
            } else {
                echo "Error con el certificado, la clave del certificado no coincide";
                die(http_response_code(404));
            }

            if (!empty($informacionForm->clave_sol)) {
                // Encriptar una cadena de texto
                $mensaje = $informacionForm->clave_sol;
                $clave = "CERTIFICADO_DIGITAL_SUNAT_VALIDO";
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                $mensaje_encriptado = openssl_encrypt($mensaje, 'aes-256-cbc', $clave, OPENSSL_RAW_DATA, $iv);
                $mensaje_encriptado = base64_encode($mensaje_encriptado . '::' . $iv);
                $CertificadoDigital += [
                    'clavesol_certificado_digital' => $mensaje_encriptado,
                ];
            }
            if (!empty($informacionForm->clave_archivo)) {
                // Encriptar una cadena de texto
                $mensaje = $informacionForm->clave_archivo;
                $clave = "CERTIFICADO_DIGITAL_SUNAT_VALIDO";
                $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
                $mensaje_encriptado = openssl_encrypt($mensaje, 'aes-256-cbc', $clave, OPENSSL_RAW_DATA, $iv);
                $mensaje_encriptado = base64_encode($mensaje_encriptado . '::' . $iv);
                $CertificadoDigital += [
                    'clavearchivo_certificado_digital' => $mensaje_encriptado
                ];
            }
        }

        if (!empty($informacionForm->id_empresa_venta_online) && $informacionForm->id_empresa_venta_online != '') {
            $Empresa = EmpresaVentaOnline::where('id_empresa_venta_online', $informacionForm->id_empresa_venta_online)->update($fillable);
            $id_empresa_venta_online = $informacionForm->id_empresa_venta_online;
        } else {
            $Empresa = EmpresaVentaOnline::create($fillable);
            $id_empresa_venta_online = $Empresa->id_empresa_venta_online;
        }

        if (isset($_FILES['archivo_digital'])) {
            $CertificadoDigital += [
                'id_empresa_venta_online' => $id_empresa_venta_online,
                'usuariosol_certificado_digital' => $informacionForm->usuario_sol
            ];
            CertificadoDigital::create($CertificadoDigital);
        }

        echo $id_empresa_venta_online;
    }

    public function TraerCertificadoEmpresa()
    {
        $CertificadoDigital = CertificadoDigital::where('id_empresa_venta_online', $_POST['id_empresa'])->get();
        echo $CertificadoDigital;
    }

    public function TraerEmpresa()
    {
        $query = "SELECT * FROM empresa_venta_online
        left join distrito using (idDistrito)
        left join provincia using (idProvincia)
        left join departamentos using (idDepartamento)
        limit 1 ";
        $ConsultaGlobal = (new ConsultaGlobal())->ConsultaGlobal($query);
        echo json_encode(count($ConsultaGlobal) > 0 ? $ConsultaGlobal[0] : null);
    }

    public function CargarPixelEmpresa()
    {
        $scripts = '<script async src="https://www.googletagmanager.com/gtag/js?id=UA-188698108-1"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() { dataLayer.push(arguments); }
                gtag("js", new Date());

                gtag("config", "UA-188698108-1");
            </script>';

        // Separar los bloques de script en un array
        $script_blocks = explode('</script>', $scripts);

        // Eliminar el último elemento del array que siempre será una cadena vacía
        array_pop($script_blocks);

        // Iterar sobre el array y agregar cada bloque de script a un arreglo separado
        $separated_scripts = array();
        foreach ($script_blocks as $block) {
            $separated_scripts[] = str_replace('<script', '', $block);
        }
        $separated_scripts_facebook = array();
        $respuesta = [
            "pixel_google" => $separated_scripts,
            "pixel_facebook" => $separated_scripts_facebook,
        ];
        echo json_encode($respuesta);
    }
}
