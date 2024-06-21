<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class PdfCreator
{
    public function create($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream("Estadisticas_del_juego.pdf" , ['Attachment' => 0]);
    }
}

