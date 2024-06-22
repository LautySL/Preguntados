<?php

use Dompdf\Dompdf;

class PdfCreator
{
    public function create($html)
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        // Devolver el PDF generado
        return $dompdf->stream("Graficos.pdf", ['Atachment' => 0]);
    }
}

