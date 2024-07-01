<?php
class Grafico
{
    public function __construct()
    {
    }

    public function generarGraficoDeBarras($titulo, $etiquetas, $totales, $filename)
    {
        if (empty($etiquetas) || empty($totales) || count($etiquetas) !== count($totales)) {
            throw new Exception('Los datos para el gráfico de barras son inválidos o están vacíos.');
        }

        // Aquí eliminamos el formateo de fechas ya que no siempre usaremos fechas
        // $etiquetas = array_map(function ($fecha) {
        //     return date('Y-m-d', strtotime($fecha));
        // }, $etiquetas);

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

        $grafico->xaxis->SetTickLabels($etiquetas);

        $grafico->Add($barra);

        $grafico->Stroke($rutaCompleta);

        if (!file_exists($rutaCompleta)) {
            throw new Exception('Error al guardar la imagen del gráfico en el archivo.');
        }

        return $filename;
    }

    public function generarGraficoDeLineas($titulo, $etiquetas, $totales, $filename)
    {
        if (empty($etiquetas) || empty($totales) || count($etiquetas) !== count($totales)) {
            throw new Exception('Los datos para el gráfico de líneas son inválidos o están vacíos.');
        }

        // Aquí eliminamos el formateo de fechas ya que no siempre usaremos fechas
        // $etiquetas = array_map(function ($fecha) {
        //     return date('Y-m-d', strtotime($fecha));
        // }, $etiquetas);

        $totales = array_map('intval', $totales);

        $timestamp = date('Y-m-d_H-i-s');
        $filename = $filename . '_' . $timestamp . '.png';

        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/public/img/grafico/';
        $rutaCompleta = $rutaBase . $filename;

        $grafico = new Graph(600, 400, 'auto');
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);

        $lineas = new LinePlot($totales);
        $lineas->SetColor('blue');
        $lineas->SetWeight(2);

        $grafico->xaxis->SetTickLabels($etiquetas);

        $grafico->Add($lineas);

        $grafico->Stroke($rutaCompleta);

        if (!file_exists($rutaCompleta)) {
            throw new Exception('Error al guardar la imagen del gráfico en el archivo.');
        }

        return $filename;
    }
}