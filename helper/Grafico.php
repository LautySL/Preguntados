<?php

class Grafico
{
    public function __construct()
    {
        // Constructor vacío por ahora
    }

    public function generarGraficoDeBarras($titulo, $datos)
    {

        if (empty($datos) || !is_array($datos) || count($datos) === 0) {
            throw new Exception('Los datos para el gráfico de barras son inválidos o están vacíos.');
        }
        $datos = array_map('intval', $datos);

        $timestamp = date('Y-m-d_H-i-s');
        $filename = $titulo . $timestamp . '.png';

        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/public/img/grafico/';

        $rutaCompleta = $rutaBase . $filename;

        $grafico = new Graph(600, 400, 'auto');
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);

        $barra = new BarPlot($datos);
        $barra->SetColor('blue');
        $barra->SetFillColor('lightblue');
        $grafico->xaxis->SetTickLabels([$timestamp]);
        $grafico->Add($barra);
        $grafico->Stroke($rutaCompleta);

        if (!file_exists($rutaCompleta)) {
            throw new Exception('Error al guardar la imagen del gráfico en el archivo.');
        }

        return  $filename;
    }


}

