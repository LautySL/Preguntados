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
public function getPreguntasSugeridas()
{
    $query = "SELECT * FROM pregunta WHERE sugerida = 1";
    $result = $this->database->execute($query);
    $preguntas = [];

    while ($row = $result->fetch_assoc()) {
        $preguntas[] = $row;
    }

    return $preguntas;
    }

    public function getRespuestaCorrectaByPreguntaId($preguntaId)
    {
    $query = "SELECT respuesta FROM respuesta WHERE pregunta = $preguntaId AND es_la_correcta = TRUE LIMIT 1";
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

    
    public function sugerirPregunta($id, $pregunta, $respuesta, $categoria)
    {
        $queryPregunta = "INSERT INTO `pregunta`(`id`, `pregunta`, `categoría`, `veces_que_salio`, `veces_correcta`, `dificultad`, `ultima_vez_que_salio`, `fecha_creacion_pregunta`, `sugerida`) 
                                        VALUES ('$id','$pregunta','$categoria','0','0','0.00',NULL, CURRENT_TIMESTAMP, TRUE)";
        $this->database->execute($queryPregunta);
    
        $queryRespuesta = "INSERT INTO `respuesta`(`id`, `respuesta`, `es_la_correcta`, `pregunta`) 
                                        VALUES ('','$respuesta',TRUE,'$id')";
        $this->database->execute($queryRespuesta);
    
        return true;
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


    public function eliminarPreguntaSugerida($id_a_eliminar)
    {
        $query = "DELETE FROM pregunta WHERE id = '$id_a_eliminar'"; 
        $this->database->execute($query);
        return true;
    }

}