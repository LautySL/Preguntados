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

    public function totalJugadores($dateFrom, $dateTo)
    {
        $whereClause = '';

        if (!empty($dateFrom) && !empty($dateTo)) {
            $whereClause = " WHERE u.fecha_creacion >= '" . $dateFrom . "' AND u.fecha_creacion <= '" . $dateTo . "'";
        }
        
        $query = "SELECT COUNT(*) AS total_jugadores FROM jugador j INNER JOIN usuario u ON j.id = u.id" . $whereClause;
        $result = $this->database->execute($query);

        if (!$result) {
            throw new Exception("Error al ejecutar la consulta SQL");
        }

        $data = array();

        while ($row = $result->fetch_assoc()) {
            $data[] = $row['total_jugadores'];
        }

        $filename = 'total_jugadores.png';
        try {
            $this->grafico->generarGraficoDeBarras("Total de Jugadores", $data, $filename);
        } catch (Exception $e) {
            throw new Exception("Error del modelo al generar el gráfico de barras:" . $e->getMessage());
        }

        $data = ['total_jugadores' => $filename];

        return $data;
    }

    public function totalPartidas()
    {
        $query = "SELECT COUNT(*) AS total_partidas FROM partida";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['total_partidas'];

    }

    public function totalPreguntas()
    {
        $query = "SELECT COUNT(*) AS total_preguntas FROM pregunta";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['total_preguntas'];
    }

    //TODO por que hay que agregar al editor y que este pueda dar de alta las preguntas
    public function totalPreguntasCreadas()
    {
        $query = "SELECT COUNT(*) AS total_preguntas_creadas FROM pregunta WHERE DATE(fecha_creacion) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        $result = $this->database->execute($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_preguntas_creadas'];
        } else {
            // Manejar el caso de error o resultado vacío
            return 0; // O un manejo de error adecuado según tu aplicación
        }
    }

    //TODO por que hay que modificar la bd para que ande agregandole una fecha de creacion a cada user
    public function usuariosNuevos()
    {
        $query = "SELECT * AS usuarios_nuevos FROM usuario WHERE id > 11";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['usuarios_nuevos'];
    }

    public function totalCorrectas()
    {
        $query = "SELECT p.categoría,
                    COUNT(*) AS total_preguntas,
                    SUM(pp.se_respondio_bien) AS total_correctas,
                    ROUND((SUM(pp.se_respondio_bien) / COUNT(*)) * 100, 2) AS porcentaje_correctas
                    FROM pregunta p
                    LEFT JOIN partida_pregunta pp ON p.id = pp.pregunta
                    GROUP BY p.categoría;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorPais()
    {
        $query = "SELECT pais, COUNT(*) AS total_usuarios_por_pais
                    FROM usuario 
                    GROUP BY pais;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS total_usuarios_por_sexo
                    FROM usuario 
                    GROUP BY sexo;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorRango()
    {
        $query = "SELECT CASE
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) < 18 THEN 'menor'
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) >= 65 THEN 'jubilado'
                        ELSE 'medio'
                    END AS rango_etario,
                        COUNT(*) AS total_usuarios_por_rango
                                FROM usuario
                                GROUP BY rango_etario;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
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