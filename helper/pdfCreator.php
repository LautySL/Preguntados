<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfCreator
{
    public function create($html)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        return $dompdf->stream("Graficos.pdf", ['Attachment' => 0]);
    }
}

