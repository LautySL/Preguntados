<?php

class AdminModel
{
    private $database;
    private $grafico;
    private $pdf;

    public function __construct($database, $grafico, $pdf)
    {
        $this->database = $database;
        $this->grafico = $grafico;
        $this->pdf = $pdf;
    }

    private function ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, $titulo, $nombreArchivo)
    {
        $query = $this->construirConsulta($tabla, $columnas, $condiciones, $agrupaciones);
        $result = $this->database->execute($query);

        if (!$result) {
            throw new Exception("Error al ejecutar la consulta SQL");
        }

        $etiquetas = [];
        $totales = [];

        while ($row = $result->fetch_assoc()) {
            $etiquetas[] = $row['etiqueta'];
            $totales[] = intval($row['total']);
        }

        if (empty($etiquetas) || empty($totales)) {
            throw new Exception('No se encontraron datos para generar el gráfico.');
        }

        $filenameBarra = $nombreArchivo . '_barra';
        $filenameLinea = $nombreArchivo . '_linea';
        try {
            $filenameBarra = $this->grafico->generarGraficoDeBarras($titulo, $etiquetas, $totales, $filenameBarra);
            $filenameLinea = $this->grafico->generarGraficoDeLineas($titulo, $etiquetas, $totales, $filenameLinea);
        } catch (Exception $e) {
            throw new Exception("Error del modelo al generar el gráfico: " . $e->getMessage());
        }

        return [
            'filenameBarra' => $filenameBarra,
            'filenameLinea' => $filenameLinea,
        ];
    }

    private function construirConsulta($tabla, $columnas, $condiciones, $agrupaciones)
    {
        $query = "SELECT " . implode(', ', $columnas) . " FROM " . $tabla;

        if (!empty($condiciones)) {
            $query .= " WHERE " . implode(' AND ', $condiciones);
        }

        if (!empty($agrupaciones)) {
            $query .= " GROUP BY " . implode(', ', $agrupaciones);
        }

        return $query;
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

    private function construirCondicionesFecha($campoFecha, $dateFrom, $dateTo)
    {
        $condiciones = [];
        if (!empty($dateFrom) && !empty($dateTo)) {
            $dateFrom = mysqli_real_escape_string($this->database->getConnection(), $dateFrom);
            $dateTo = mysqli_real_escape_string($this->database->getConnection(), $dateTo);
            $condiciones[] = "DATE($campoFecha) >= '$dateFrom'";
            $condiciones[] = "DATE($campoFecha) <= '$dateTo'";
        }
        return $condiciones;
    }

    public function totalJugadores($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('u.fecha_creacion', $dateFrom, $dateTo);

        $tabla = 'jugador j INNER JOIN usuario u ON j.id = u.id';
        $columnas = [
            'DATE(u.fecha_creacion) as etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'DATE(u.fecha_creacion)'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Jugadores", 'total_jugadores');
    }

    public function totalPartidas($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion_partida', $dateFrom, $dateTo);

        $tabla = 'partida';
        $columnas = [
            'DATE(fecha_creacion_partida) as etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'DATE(fecha_creacion_partida)'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Partidas", 'total_partidas');
    }

    public function totalPreguntas($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion_pregunta', $dateFrom, $dateTo);

        $tabla = 'pregunta';
        $columnas = [
            'DATE(fecha_creacion_pregunta) as etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'DATE(fecha_creacion_pregunta)'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Preguntas", 'total_preguntas');
    }

    public function totalPreguntasCreadas($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion_pregunta', $dateFrom, $dateTo);

        $tabla = 'preguntas_sugeridas';
        $columnas = [
            'DATE(fecha_creacion_pregunta) as etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'DATE(fecha_creacion_pregunta)'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Preguntas Creadas", 'total_preguntas_creadas');
    }

    public function usuariosNuevos($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion', $dateFrom, $dateTo);

        $tabla = 'usuario';
        $columnas = [
            'DATE(fecha_creacion) as etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'DATE(fecha_creacion)'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Usuarios Nuevos", 'total_usuarios_nuevos');
    }

    public function totalCorrectas($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('p.fecha_creacion_pregunta', $dateFrom, $dateTo);

        $tabla = 'pregunta p 
              LEFT JOIN partida_pregunta pp ON p.id = pp.pregunta 
              LEFT JOIN partida pa ON pp.partida = pa.id 
              LEFT JOIN jugador j ON pa.jugador = j.id 
              LEFT JOIN usuario u ON j.id = u.id';
        $columnas = [
            'u.nombre_de_usuario as etiqueta',
            'COUNT(*) AS total_preguntas',
            'SUM(pp.se_respondio_bien) AS total',
            'ROUND((SUM(pp.se_respondio_bien) / COUNT(*)) * 100, 2) AS porcentaje_correctas'
        ];
        $agrupaciones = [
            'u.nombre_de_usuario'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Preguntas Correctas", 'total_preguntas_correctas');
    }

    public function totalUsuariosPorPais($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion', $dateFrom, $dateTo);

        $tabla = 'usuario';
        $columnas = ['pais AS etiqueta', 'COUNT(*) AS total'];
        $agrupaciones = ['pais'];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Usuarios por País", 'total_usuarios_por_pais');
    }

    public function totalUsuariosPorSexo($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion', $dateFrom, $dateTo);

        $tabla = 'usuario';
        $columnas = ['sexo AS etiqueta', 'COUNT(*) AS total'];
        $agrupaciones = ['sexo'];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Total de Usuarios por Sexo", 'total_usuarios_por_sexo');
    }

    public function totalUsuariosPorRango($dateFrom, $dateTo)
    {
        $condiciones = $this->construirCondicionesFecha('fecha_creacion', $dateFrom, $dateTo);

        $tabla = 'usuario';
        $columnas = [
            'CASE
            WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) < 18 THEN \'menor\'
            WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) >= 65 THEN \'jubilado\'
            ELSE \'medio\'
        END AS etiqueta',
            'COUNT(*) AS total'
        ];
        $agrupaciones = [
            'etiqueta'
        ];

        return $this->ejecutarConsulta($tabla, $columnas, $condiciones, $agrupaciones, "Usuarios por edad", 'usuarios_por_edad');
    }
}