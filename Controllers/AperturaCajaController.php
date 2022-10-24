<?php
use Dompdf\Dompdf;
class AperturaCajaController{
    public function ReporteCajaCerrado(){
        $dompdf = new Dompdf();
        $dompdf->loadHtml('hello world');
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');
        // Render the HTML as PDF
        $dompdf->render();
        $fecha=date("Y-m-d");
        // Output the generated PDF to Browser
        $dompdf->stream("Cierre Caja $fecha.pdf",array("Attachment"=>0));
    }

}