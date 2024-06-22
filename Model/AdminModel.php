<?php

class AdminModel
{

    private $database;
    private $grafico;

    public function __construct($database, $grafico)
    {
        $this->database = $database;

        $this->grafico = $grafico;
    }

    private function ejecutarConsulta($query, $titulo, $nombreArchivo)
    {
        $result = $this->database->execute($query);

        if (!$result) {
            throw new Exception("Error al ejecutar la consulta SQL");
        }

        $fechas = [];
        $totales = [];

        while ($row = $result->fetch_assoc()) {
            $fechas[] = $row['fecha'];
            $totales[] = intval($row['total']);
        }

        if (empty($fechas) || empty($totales)) {
            throw new Exception('No se encontraron datos para generar el gráfico de barras.');
        }

        $filename = $nombreArchivo;
        try {
            $filename = $this->grafico->generarGraficoDeBarras($titulo, $fechas, $totales, $filename);
        } catch (Exception $e) {
            throw new Exception("Error del modelo al generar el gráfico de barras:" . $e->getMessage());
        }

        return ['filename' => $filename];
    }

    private function construirWhereClauseFecha($campoFecha, $dateFrom, $dateTo)
    {
        $whereClause = '';

        if (!empty($dateFrom) && !empty($dateTo)) {
            $dateFrom = mysqli_real_escape_string($this->database->getConnection(), $dateFrom);
            $dateTo = mysqli_real_escape_string($this->database->getConnection(), $dateTo);
            $whereClause = " WHERE DATE($campoFecha) >= '" . $dateFrom . "' AND DATE($campoFecha) <= '" . $dateTo . "'";
        }

        return $whereClause;
    }

    public function totalJugadores($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('u.fecha_creacion', $dateFrom, $dateTo);
        $query = "SELECT DATE(u.fecha_creacion) as fecha, COUNT(*) AS total 
                    FROM jugador j 
                    INNER JOIN usuario u ON j.id = u.id" . $whereClause . "
                    GROUP BY DATE(u.fecha_creacion)";

        return $this->ejecutarConsulta($query, "Total de Jugadores", 'total_jugadores');
    }

    public function totalPartidas($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion_partida', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion_partida) as fecha, COUNT(*) AS total 
                    FROM partida" . $whereClause . "
                    GROUP BY DATE(fecha_creacion_partida)";

        return $this->ejecutarConsulta($query, "Total de Partidas", 'total_partidas');
    }


    public function totalPreguntas($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion_pregunta', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion_pregunta) as fecha, COUNT(*) AS total 
                    FROM pregunta". $whereClause . "
                    GROUP BY DATE(fecha_creacion_pregunta)";

        return $this->ejecutarConsulta($query, "Total de Preguntas", 'total_preguntas');
    }

    public function totalPreguntasCreadas($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion_pregunta', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion_pregunta) as fecha, COUNT(*) AS total 
                    FROM preguntas_sugeridas". $whereClause . "
                    GROUP BY DATE(fecha_creacion_pregunta)";

        return $this->ejecutarConsulta($query, "Total de Preguntas Creadas", 'total_preguntas_creadas');
    }

    public function usuariosNuevos($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion) as fecha, COUNT(*) AS total 
                    FROM usuario ". $whereClause . "
                    GROUP BY DATE(fecha_creacion)";

        return $this->ejecutarConsulta($query, "Total de Usuarios Nuevos", 'total_usuarios_nuevos');
    }


    public function totalCorrectas($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('p.fecha_creacion_pregunta', $dateFrom, $dateTo);
        $query = "SELECT DATE(p.fecha_creacion_pregunta) as fecha, p.categoría,
                    COUNT(*) AS total_preguntas,
                    SUM(pp.se_respondio_bien) AS total_correctas,
                    ROUND((SUM(pp.se_respondio_bien) / COUNT(*)) * 100, 2) AS porcentaje_correctas
                    FROM pregunta p
                    LEFT JOIN partida_pregunta pp ON p.id = pp.pregunta" . $whereClause . "
                    GROUP BY DATE(p.fecha_creacion_pregunta), p.categoría";

        return $this->ejecutarConsulta($query, "Total de Preguntas Correctas", 'total_preguntas_correctas');
    }

    public function totalUsuariosPorPais($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion) as fecha,  pais, COUNT(*) AS total
                    FROM usuario ". $whereClause . "
                    GROUP BY DATE(fecha_creacion), pais";

        return $this->ejecutarConsulta($query, "Total de Usuarios por Pais", 'total_usuarios_por_pais');
    }

    public function totalUsuariosPorSexo($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion) as fecha, sexo, COUNT(*) AS total
                    FROM usuario ". $whereClause . "
                    GROUP BY DATE(fecha_creacion), sexo;";

        return $this->ejecutarConsulta($query, "Total de Usuarios por Sexo", 'total_usuarios_por_sexo');
    }

    public function totalUsuariosPorRango($dateFrom, $dateTo)
    {
        $whereClause = $this->construirWhereClauseFecha('fecha_creacion', $dateFrom, $dateTo);
        $query = "SELECT DATE(fecha_creacion) as fecha,  CASE
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) < 18 THEN 'menor'
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) >= 65 THEN 'jubilado'
                        ELSE 'medio'
                    END AS rango_etario,
                        COUNT(*) AS total
                                FROM usuario ". $whereClause . "
                                GROUP BY DATE(fecha_creacion), rango_etario;";
        
        return $this->ejecutarConsulta($query, "Usuarios por edad", 'usuarios_por_edad');
    }

    // public function getPDF($data)
    // {

    //     $pdf = new FPDF();
    //     $pdf->AddPage("P", "A3");
    //     $pdf->SetFont('Arial', 'B', 14);
    //     $pageWidth = $pdf->GetPageWidth();
    //     $pageHeight = $pdf->GetPageHeight();

    //     if(empty($data['dateFrom']) || empty($data['dateTo'])){
    //         $data['dateFrom'] = "desde el inicio";
    //         $data['dateTo'] = "hasta hoy";
    //     }

    //     $pdf->Cell(40, 10, "Fechas:");
    //     $pdf->Cell(40, 10, $data['dateFrom'], 1, 0, 'C');
    //     $pdf->Cell(10, 10, " - ", 1, 0, 'C');
    //     $pdf->Cell(40, 10, $data['dateTo'], 1, 1, 'C');

    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $pdf->Cell($pageWidth / 2, 10, "Total de jugadores", 1, 0, 'C');
    //     $pdf->Cell($pageWidth / 3, 10, $data['total_players'], 1, 1, 'C');

    //     $pdf->Cell($pageWidth / 2, 10, "Total de partidas", 1, 0, 'C');
    //     $pdf->Cell($pageWidth / 3, 10, $data['total_games'], 1, 1, 'C');

    //     $pdf->Cell($pageWidth / 2, 10, "Preguntas activas", 1, 0, 'C');
    //     $pdf->Cell($pageWidth / 3, 10, $data['total_questions_active'], 1, 1, 'C');

    //     $pdf->Cell($pageWidth / 2, 10, "Total de sugerencias", 1, 0, 'C');
    //     $pdf->Cell($pageWidth / 3, 10, $data['total_suggestions'], 1, 1, 'C');

    //     $pdf->Cell($pageWidth / 2, 10, "Total de sugerencias vistas", 1, 0, 'C');
    //     $pdf->Cell($pageWidth / 3, 10, $data['total_viwed_suggestions'], 1, 1, 'C');


    //     $pdf->Cell(0, 20, "", 0, 1, "C");
    //     $pdf->Cell(40, 10, "Efectividad por usuario:");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $usersDifficulty = $this->generateArray($data['percentage_effective_for_player']);
    //     foreach ($usersDifficulty as $entry) {
    //         $pdf->Cell($pageWidth / 4, 10, "ID Cuenta: " . $entry['id_cuenta'], 1, 0, 'L');
    //         $pdf->Cell($pageWidth / 4, 10, "Usuario: " . $entry['Usuario'], 1, 0, 'L');
    //         $pdf->Cell($pageWidth / 4, 10, "Dificultad: " . $entry['dificultad'], 1, 1, 'L');
    //     }


    //     $pdf->Cell(0, 20, "", 0, 1, "C");
    //     $pdf->Cell(40, 10, "Cantidad de usuarios nuevos:");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $newUsers = $this->generateArray($data['total_new_users']);
    //     foreach ($newUsers as $entry) {
    //         $pdf->Cell($pageWidth / 4, 10, "ID Cuenta: " . $entry['id_cuenta'], 1, 0, 'L');
    //         $pdf->Cell($pageWidth / 4, 10, "Usuario: " . $entry['Usuario'], 1, 0, 'L');
    //         $pdf->Cell($pageWidth / 4, 10, "Creado: " . $entry['fecha de creacion'], 1, 1, 'L');
    //     }

    //     $pdf->AddPage("P", "A3");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");
    //     $pdf->Cell(40, 10, "Cantidad de usuarios por pais:");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $this->saveChartImage($data["by_country"], $pdf, $pageWidth/2, $pageHeight/3.70, 0,40, "country");


    //     $pdf->Cell(0, 90, "", 0, 1, "C");
    //     $pdf->Cell(40, 10, "Cantidad de usuarios por edad:");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $this->saveChartImage($data["by_age"], $pdf, $pageWidth/2, $pageHeight/3.70, 0,150, "age");


    //     $pdf->Cell(0, 90, "", 0, 1, "C");
    //     $pdf->Cell(40, 10, "Cantidad de usuarios por genero:");
    //     $pdf->Cell(0, 20, "", 0, 1, "C");

    //     $this->saveChartImage($data["by_gender"], $pdf, $pageWidth/2, $pageHeight/3.70, 0,260, "gender");


    //     $pdf->Output();
    // }

    // private function generateArray($string)
    // {

    //     $rows = explode("\n", $string); // Separar por saltos de línea

    //     $data = array();

    //     foreach ($rows as $row) {
    //         $fields = explode(" - ", $row); // Separar cada campo en la línea por el delimitador " - "

    //         $entry = array();

    //         foreach ($fields as $field) {
    //             list($key, $value) = explode(": ", $field); // Separar la clave y el valor por el delimitador ": "

    //             $entry[$key] = $value;
    //         }

    //         $data[] = $entry;
    //     }

    //     return $data;

    // }

    // private function convertToInt($result)
    // {
    //     foreach ($result as &$dato) {
    //         $dato[1] = intval($dato[1]);
    //     }

    //     return $result;
    // }

    // private function saveChartImage($chart, $pdf, $width, $height, $x, $y, $key)
    // {
    //     $imageData = $chart;
    //     $decodedImage = base64_decode(substr($imageData, strpos($imageData, ',') + 1));

    //     $imagePath = "./public/charts/" . $key . ".png";

    //     file_put_contents($imagePath, $decodedImage);


    //     $pdf->Image($imagePath, $x, $y, $width, $height);

    //     unlink($imagePath);

    // }
}