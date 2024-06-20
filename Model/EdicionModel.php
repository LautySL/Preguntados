<?php

class EdicionModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function getPreguntas()
    {
    $query = "SELECT * FROM pregunta";
    $result = $this->database->execute($query);
    $preguntas = [];

    while ($row = $result->fetch_assoc()) {
        $preguntas[] = $row;
    }

    return $preguntas;
    }

    public function getPreguntasSugeridas()
    {
        $query = "SELECT * FROM preguntas_sugeridas";
        $result = $this->database->execute($query);
        $preguntas = [];

        while ($row = $result->fetch_assoc()) {
            $preguntas[] = $row;
        }

        return $preguntas;
    }



public function getPreguntaById($id)
    {
    $query = "SELECT * FROM pregunta WHERE id = $id";
    $result = $this->database->execute($query);
    return $result;
    }

    public function getCategoriaByReporte($pregunta_id)
    {
    $query = "SELECT * FROM reportes_preguntas WHERE pregunta_id = $pregunta_id";
    $result = $this->database->execute($query);
    return $result;
    }

public function getPreguntasReportadas()
    {
    $query = "SELECT rp.id AS reporte_id, rp.fecha_reporte, p.pregunta, r.respuesta
              FROM reportes_preguntas rp
              JOIN pregunta p ON rp.pregunta_id = p.id
              JOIN respuesta r ON p.id = r.pregunta
              ORDER BY rp.fecha_reporte DESC";

    $result = $this->database->execute($query);
    $preguntas = [];

    while ($row = $result->fetch_assoc()) {
        $preguntas[] = $row;
    }

    return $preguntas;
    }

    public function getRespuestaCorrectaByPreguntaId($preguntaId)
    {
        $query = "SELECT respuesta FROM respuestas_sugeridas WHERE pregunta = $preguntaId AND es_la_correcta = TRUE LIMIT 1";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['respuesta'] ?? null;
    }

    public function modificarPregunta($id, $nuevaPregunta, $nuevaRespuesta)
    {
        $queryPregunta = "UPDATE pregunta SET pregunta = '$nuevaPregunta' WHERE id = $id";
        $this->database->execute($queryPregunta);
    
        $queryRespuesta = "UPDATE respuesta SET respuesta = '$nuevaRespuesta' WHERE pregunta = $id AND es_la_correcta = TRUE";
        $this->database->execute($queryRespuesta);
    
        return true;
    }

    public function aprobarPreguntaSugerida($id)
    {
        $this->database->begin_transaction();

        try {
            // Obtener pregunta y respuestas sugeridas
            $queryPregunta = "SELECT * FROM preguntas_sugeridas WHERE id = $id";
            $pregunta = $this->database->execute($queryPregunta)->fetch_assoc();

            $queryRespuestas = "SELECT * FROM respuestas_sugeridas WHERE pregunta = $id";
            $respuestas = $this->database->execute($queryRespuestas);

            // Insertar en tabla pregunta
            $queryInsertPregunta = "INSERT INTO pregunta (pregunta, fecha_creacion_pregunta) VALUES ('{$pregunta['pregunta']}', NOW())";
            $this->database->execute($queryInsertPregunta);
            $nuevoIdPregunta = $this->database->getLastInsertId();

            // Insertar respuestas
            while ($respuesta = $respuestas->fetch_assoc()) {
                $queryInsertRespuesta = "INSERT INTO respuesta (pregunta, respuesta, es_la_correcta) VALUES ($nuevoIdPregunta, '{$respuesta['respuesta']}', {$respuesta['es_la_correcta']})";
                $this->database->execute($queryInsertRespuesta);
            }

            // Eliminar de preguntas y respuestas sugeridas
            $queryDeletePregunta = "DELETE FROM preguntas_sugeridas WHERE id = $id";
            $this->database->execute($queryDeletePregunta);

            $queryDeleteRespuestas = "DELETE FROM respuestas_sugeridas WHERE pregunta = $id";
            $this->database->execute($queryDeleteRespuestas);

            $this->database->commit();
            return true;
        } catch (Exception $e) {
            $this->database->rollback();
            return false;
        }
    }

    public function rechazarPreguntaSugerida($id)
    {
        try {
            $queryDeletePregunta = "DELETE FROM preguntas_sugeridas WHERE id = $id";
            $this->database->execute($queryDeletePregunta);

            $queryDeleteRespuestas = "DELETE FROM respuestas_sugeridas WHERE pregunta = $id";
            $this->database->execute($queryDeleteRespuestas);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function eliminarPregunta($id_a_eliminar)
    {
        $query = "DELETE FROM pregunta WHERE id = '$id_a_eliminar'"; 
        $this->database->execute($query);
        return true;
    }

    public function eliminarPreguntaReportada($id_reporte)
    {
        $queryReporte = "DELETE FROM reportes_preguntas WHERE id = '$id_reporte'"; 
        $this->database->execute($queryReporte);

        $queryPregunta = "DELETE FROM pregunta WHERE id = (
            SELECT pregunta_id FROM reportes_preguntas WHERE id = '$id_reporte'
        )";
        $this->database->execute($queryPregunta);
    
        return true;
    }


}