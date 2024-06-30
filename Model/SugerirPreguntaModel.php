<?php

class SugerirPreguntaModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addSuggestedQuestion($pregunta, $categoria, $usuario_id, $respuestas) {
        // Inserta la pregunta sugerida
        $sql = "INSERT INTO preguntas_sugeridas (pregunta, categoría, usuario_id) VALUES ('$pregunta', '$categoria', $usuario_id)";
        if ($this->db->execute($sql)) {
            $pregunta_id = $this->db->getLastInsertId();

            // Inserta las respuestas sugeridas
            foreach ($respuestas as $respuesta) {
                $respuesta_texto = $respuesta['respuesta'];
                $es_correcta = $respuesta['es_correcta'];
                $sql = "INSERT INTO respuestas_sugeridas (pregunta, respuesta, es_la_correcta) VALUES ($pregunta_id, '$respuesta_texto', $es_correcta)";
                $this->db->execute($sql);
            }

            return true;
        } else {
            return false;
        }
    }
}