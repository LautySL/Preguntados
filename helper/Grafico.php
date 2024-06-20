<?php
class Grafico
{
    public function __construct()
    {
    }

    public function generarGraficoDeBarras($titulo, $datos, $etiquetas, $filename)
    {
        $grafico = new Graph(600, 400, 'auto');
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);
        $grafico->xaxis->SetTickLabels($etiquetas);

        $barra = new BarPlot($datos);
        $barra->SetColor('blue');
        $barra->SetFillColor('lightblue');

        $grafico->Add($barra);

        $grafico->Stroke($filename);
    }
}
