<?php

class Grafico
{
    public function __construct()
    {
    }

    public function generarGraficoDeBarras($titulo, $fechas, $totales, $filename)
    {

        if (empty($fechas) || empty($totales) || count($fechas) !== count($totales)) {
            throw new Exception('Los datos para el gráfico de barras son inválidos o están vacíos.');
        }

        $fechas = array_map(function ($fecha) {
            return date('Y-m-d', strtotime($fecha));
        }, $fechas);

        $totales = array_map('intval', $totales);

        $timestamp = date('Y-m-d_H-i-s');
        $filename = $filename . '_' . $timestamp . '.png';

        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/public/img/grafico/';

        $rutaCompleta = $rutaBase . $filename;

        $grafico = new Graph(600, 400, 'auto');
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);

        $barra = new BarPlot($totales);
        $barra->SetColor('blue');
        $barra->SetFillColor('lightblue');

        $grafico->xaxis->SetTickLabels($fechas);

        $grafico->Add($barra);

        $grafico->Stroke($rutaCompleta);

        if (!file_exists($rutaCompleta)) {
            throw new Exception('Error al guardar la imagen del gráfico en el archivo.');
        }

        return  $filename;
    }


}

