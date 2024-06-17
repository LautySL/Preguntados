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
        // Agregar cada fila (pregunta) al array de preguntas
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


    public function eliminarPregunta($id_a_eliminar)
    {
        $query = "DELETE FROM pregunta WHERE id = '$id_a_eliminar'"; 
        $this->database->execute($query);
        return true;
    }


    public function getPreguntasReportadas()
    {

    }

    public function getPreguntasSugeridas()
    {
  
    }

}