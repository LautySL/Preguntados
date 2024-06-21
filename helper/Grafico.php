<?php

class Grafico
{
    public function __construct()
    {
        // Constructor vacío por ahora
    }

    public function generarGraficoDeBarras($titulo, $datos, $filename)
    {
        // Verificar si hay datos para generar el gráfico
        if (empty($datos) || !is_array($datos) || count($datos) === 0) {
            throw new Exception('Los datos para el gráfico de barras son inválidos o están vacíos.');
        }

        // Convertir los datos a números enteros (si es necesario)
        $datos = array_map('intval', $datos);

        $rutaBase = 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'grafico' . DIRECTORY_SEPARATOR;

        // Construir la ruta completa del archivo de imagen
        $rutaCompleta = $rutaBase . $filename;

        // Crear el gráfico de barras
        $grafico = new Graph(600, 400, 'auto');
        $grafico->SetScale('textlin');
        $grafico->title->Set($titulo);
        //$grafico->xaxis->SetTickLabels($etiquetas);

        $barra = new BarPlot($datos);
        $barra->SetColor('blue');
        $barra->SetFillColor('lightblue');
        $grafico->Add($barra);

        ob_start();
        $grafico->Stroke($filename);
        $graphImage = ob_get_clean();
    
        if (!file_put_contents($rutaCompleta, $graphImage)) {
            throw new Exception('Error al guardar la imagen del gráfico en el archivo.');
        }

        // Generar el gráfico y guardar en el archivo especificado
        // try {
        //     $grafico->Stroke($filename);
        // } catch (Exception $e) {
        //     // Manejar cualquier excepción durante la generación del gráfico
        //     throw new Exception('Error de la clase grafico al generar el gráfico de barras:' . $e->getMessage());
        //}
    }
}

