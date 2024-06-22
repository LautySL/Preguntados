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

        $filenameBarra = $nombreArchivo . '_barra'; 
        $filenameLinea = $nombreArchivo . '_linea';
        try {
            $filenameBarra = $this->grafico->generarGraficoDeBarras($titulo, $fechas, $totales, $filenameBarra);
            $filenameLinea = $this->grafico->generarGraficoDeLineas($titulo, $fechas, $totales, $filenameLinea);
        } catch (Exception $e) {
            throw new Exception("Error del modelo al generar el gráfico de barras:" . $e->getMessage());
        }

        return [
            'filenameBarra' => $filenameBarra,
            'filenameLinea' => $filenameLinea,
        ];
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
                    SUM(pp.se_respondio_bien) AS total,
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

}