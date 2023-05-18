<?php

use Greenter\See;
use Greenter\Ws\Services\SunatEndpoints;
use Greenter\XMLSecLibs\Certificate\X509Certificate;
use Greenter\XMLSecLibs\Certificate\X509ContentType;

class Helper
{

    public function getBytesFromHexString($hexdata)
    {
        for ($count = 0; $count < strlen($hexdata); $count += 2)
            $bytes[] = chr(hexdec(substr($hexdata, $count, 2)));

        return implode($bytes);
    }

   public function getImageMimeType($imagedata)
    {
        $imagemimetypes = array(
            "jpeg" => "FFD8",
            "png" => "89504E470D0A1A0A",
            "gif" => "474946",
            "bmp" => "424D",
            "tiff" => "4949",
            "tiff" => "4D4D"
        );

        foreach ($imagemimetypes as $mime => $hexbytes) {
            $bytes =$this->getBytesFromHexString($hexbytes);
            if (substr($imagedata, 0, strlen($bytes)) == $bytes)
                return $mime;
        }

        return null;
    }
    public static function identificacionDocumentoPruebas()
    {
        $see = new See();
        $see->setCertificate(file_get_contents(__DIR__ .'/../archivo/certificados/certificate_prueba.pem'));
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setClaveSOL('20000000001', 'MODDATOS', 'moddatos');
        return $see;
    }
    public static function identificacionDocumentoProduccion($datosEmpresa=[])
    {
        $pfx = file_get_contents(__DIR__ ."/../archivo/certificado_digital/{$datosEmpresa['path_certificado_digital']}");
        $password = $datosEmpresa['clave_certificado'];
        $certificate = new X509Certificate($pfx, $password);
        $see = new See();
        $see->setCertificate($certificate->export(X509ContentType::PEM));
        $see->setService(SunatEndpoints::FE_PRODUCCION);
        $see->setClaveSOL($datosEmpresa['ruc_empresa'],$datosEmpresa['usuario_sol'],$datosEmpresa['clave_sol']);//'10157622680' / 'CALEL019'  /  'Durand019'
        return $see;
    }
}
