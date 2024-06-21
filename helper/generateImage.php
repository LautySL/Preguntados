<?php
// generateGraph.php - Script para generar gráfico con JPGraph

require_once 'vendor/jpgraph/src/jpgraph.php';
require_once 'vendor/jpgraph/src/jpgraph_line.php';

// Obtener datos del parámetro GET (validar y sanitizar adecuadamente)
$data = json_decode($_GET['data'], true);

// Preparar datos para JPGraph
$fechas = array_column($data, 'fecha');
$cantidades = array_column($data, 'cantidad');

// Configurar el gráfico
$graph = new Graph(800, 600);
$graph->SetScale('textlin');

$theme = new UniversalTheme;
$graph->SetTheme($theme);
$graph->img->SetAntiAliasing(true);
$graph->title->Set('Datos estadísticos');
$graph->SetBox(false);

$graph->SetMargin(40, 20, 36, 63);

$graph->yaxis->HideZeroLabel();
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false, false);

$graph->xgrid->Show();
$graph->xgrid->SetLineStyle('solid');
$graph->xaxis->SetTickLabels($fechas);
$graph->xgrid->SetColor('#E3E3E3');

// Crear la línea del gráfico
$p1 = new LinePlot($cantidades);
$graph->Add($p1);
$p1->SetColor('#6495ED');
$p1->SetLegend('Cantidad');

// Mostrar el gráfico
$graph->legend->SetFrameWeight(1);
$graph->Stroke();