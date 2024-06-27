<?php

class GameModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function obtenerDataParaPartida($idUsuario, $puntaje, $finalizado, $puntajeFinal): array
    {
        $preguntaData = $this->obtenerPreguntaYRespuestas($idUsuario);

        $data = [
            'nombreUsuario' => $_SESSION['usuario'],
            'pregunta' => $preguntaData['pregunta'],
            'pregunta_id' => $preguntaData['pregunta_id'],
            'respuestas' => $preguntaData['respuestas'],
            'categoria' => $preguntaData['categoria'],
            'categoria_estilo' => $this->obtenerEstiloCategoria($preguntaData['categoria']),
            'puntaje' => $puntaje,
            'finalizado' => $finalizado,
            'time_left' => $this->getTimeLeft(),
            'token' => $this->getCantidadToken($idUsuario),
            'dificultad_user' => $this->obtenerPorcentajeAciertos($idUsuario),
            'dificultad' => $preguntaData['dificultad'],
            'puntaje-bot' => $_SESSION["puntaje-bot"],
            'resultado-versus' =>  $_SESSION["resultado-versus"],
            'modo_versus' => $_SESSION["modo_versus"]
        ];
        return $data;
    }

    public function procesarRespuesta($idPartida, $preguntaId, $respuestaId, $puntaje)
    {
        $esCorrecta = $this->verificarYGuardarRespuesta($idPartida, $preguntaId, $respuestaId) && $this->checkTimeLimit();

        if ($esCorrecta) {
            $puntaje++;
            $_SESSION['puntaje'] = $puntaje;
            $this->actualizarPuntajeFinal($idPartida, $puntaje);
            return true;
        } else {
            return false;
        }
    }

    public function verificarYGuardarRespuesta($idPartida, $preguntaId, $respuestaId)
    {
        $esCorrecta = $this->esRespuestaCorrecta($preguntaId, $respuestaId);

        $this->guardarRespuestaEnPartida($idPartida, $preguntaId, $esCorrecta);
        return $esCorrecta;
    }

    public function getTimeLeft()
    {
        if (!isset($_SESSION['start_time'])) {
            return 30;
        }

        $elapsedTime = time() - $_SESSION['start_time'];
        $timeLeft = 30 - $elapsedTime;

        return max(0, $timeLeft);
    }

    public function crearPartida($idUsuario)
    {
        try {
            $idUsuario = intval($idUsuario);
    
            $queryInsertPartida = "INSERT INTO partida (puntaje, jugador, fecha_creacion_partida) VALUES (0, $idUsuario, CURRENT_TIMESTAMP)";
            $this->database->execute($queryInsertPartida);
    
            $partidaId = $this->database->getLastInsertId();
    
            return $partidaId;
        } catch (Exception $e) {
            echo "Error al crear la partida: " . $e->getMessage();
            return null;
        }
    }
    
    public function crearPartidaVersusBot($idUsuario)
    {
        $idUsuario = intval($idUsuario);
    
        $queryInsertPartida = "INSERT INTO partida (puntaje, jugador, fecha_creacion_partida, modo_versus) VALUES (0, $idUsuario, CURRENT_TIMESTAMP, true)";
        $this->database->execute($queryInsertPartida);
    
        $partidaId = $this->database->getLastInsertId();
        return $partidaId;
    }
    
    public function partidaBot()
    {
        $resultado = rand(0, 20);
        return $resultado;
    }
    
    public function compararResultados($idPartida)
    {
        if (!isset($_SESSION['puntaje'])) {
            $_SESSION['puntaje'] = 0;
        }
        $partidaNoBot = $_SESSION['puntaje'];
        $partidaBot = $this->partidaBot();
        $_SESSION["puntaje-bot"] = $partidaBot;
        $_SESSION["resultado-versus"] = "Empataste";
    
        if ($partidaNoBot > $partidaBot) {
            $sql = "UPDATE partida SET resultado_versus = 'Ganada' WHERE id = '$idPartida'";
            $this->database->execute($sql);
            $_SESSION["resultado-versus"] = "Ganaste";
        } else if ($partidaNoBot < $partidaBot) {
            $sql = "UPDATE partida SET resultado_versus = 'Perdida' WHERE id = '$idPartida'";
            $this->database->execute($sql);
            $_SESSION["resultado-versus"] = "Perdiste";
        }
    }
    
    public function actualizarPuntajeFinal($idPartida, $puntaje)
    {
        try {
            $queryUpdatePartida = "UPDATE partida SET puntaje = '$puntaje' WHERE id = '$idPartida'";
            $this->database->execute($queryUpdatePartida);
        } catch (Exception $e) {
            echo "Error al actualizar el puntaje final: " . $e->getMessage();
        }
    }

    public function reportarPregunta($preguntaId, $idUsuario)
    {
        try {
            $queryreport = "INSERT INTO reportes_preguntas (pregunta_id, usuario_id) VALUES ($preguntaId,$idUsuario)";
            ;
            $this->database->execute($queryreport);
        } catch (Exception $e) {
            echo "Error al reportar: " . $e->getMessage();

        }
    }

    public function obtenerRespuestaCorrectaId($preguntaId)
    {
        $query = "SELECT id FROM respuesta WHERE pregunta = '$preguntaId' AND es_la_correcta = 1";
        $result = $this->database->execute($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['id'];
        } else {
            return null;
        }
    }

    public function verificarSiTieneTokenYusarlo($id)
    {
        $cantidadTokens = $this->getCantidadToken($id);

        if ($cantidadTokens > 0) {
            $query = "UPDATE usuario SET token = token - 1 WHERE id = $id";
            $this->database->execute($query);
            return true;
        }
        return false;
    }

    private function esRespuestaCorrecta($preguntaId, $respuestaId)
    {
        $query = "SELECT es_la_correcta FROM respuesta WHERE id = '$respuestaId' AND pregunta = '$preguntaId'";
        $result = $this->database->execute($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (bool) $row['es_la_correcta'];
        } else {
            return false;
        }
    }

    private function checkTimeLimit()
    {
        $elapsedTime = time() - $_SESSION['start_time'];
        if ($elapsedTime < 30) {
            return true;
        } else {
            return false;
        }
    }

    private function guardarRespuestaEnPartida($idPartida, $preguntaId, $esCorrecta)
    {
        try {
            $queryInsertPartidaPregunta = "INSERT INTO partida_pregunta (partida, pregunta, se_respondio_bien) VALUES ('$idPartida', '$preguntaId', '$esCorrecta')";
            $this->database->execute($queryInsertPartidaPregunta);
            $this->actualizarEstadisticasPregunta($preguntaId, $esCorrecta);

        } catch (Exception $e) {
            echo "Error al guardar la respuesta: " . $e->getMessage();
        }
    }

    private function obtenerPreguntaYRespuestas($idUsuario)
    {
        $preguntas = $this->queryPregunta($idUsuario);

        if (empty($preguntas)) {

            $this->limpiarPreguntasPartida($idUsuario);
            return $this->obtenerPreguntaYRespuestas($idUsuario);

        }
        $pregunta = $preguntas[0];
        $preguntaId = (int) $pregunta['id'];


        $respuestas = $this->queryRespuestas($preguntaId);
        $resultado = [
            'dificultad' => $pregunta['dificultad'],
            'pregunta_id' => $preguntaId,
            'pregunta' => $pregunta['pregunta'],
            'respuestas' => $respuestas,
            'categoria' => $pregunta['categoría']
        ];
        return $resultado;
    }

    private function obtenerEstiloCategoria($categoria)
    {
        $categoriaEstilos = [
            'Ciencia' => 'w3-green',
            'Historia' => 'w3-purple',
            'Entretenimiento' => 'w3-blue',
            'Geografía' => 'w3-deep-orange',
            'Arte' => 'w3-red',
            'Deporte' => 'w3-dark-grey'
        ];

        return $categoriaEstilos[$categoria] ?? 'w3-light-grey';
    }

    private function limpiarPreguntasPartida($idUsuario)
    {
        $queryLimpiar = "
        DELETE pp 
        FROM partida_pregunta pp
        JOIN partida p ON pp.partida = p.id
        WHERE p.jugador = '$idUsuario'";
        $this->database->execute($queryLimpiar);
    }

    private function queryRespuestas(int $preguntaId)
    {
        $queryRespuestas = "SELECT respuesta, es_la_correcta,id FROM respuesta WHERE pregunta = $preguntaId";
        $respuestas = $this->database->query($queryRespuestas);
        shuffle($respuestas);
        return $respuestas;
    }

    private function queryPregunta($idUsuario)
    {
        $porcentajeAciertos = $this->obtenerPorcentajeAciertos($idUsuario);

        $idUsuario = (int) $idUsuario;
        $queryPregunta = "
        SELECT p.id, p.pregunta, p.categoría,p.dificultad,
               ABS(p.dificultad - $porcentajeAciertos) AS diferencia_aciertos
        FROM pregunta p
        WHERE p.id NOT IN (
            SELECT pp.pregunta 
            FROM partida_pregunta pp
            JOIN partida pa ON pp.partida = pa.id
            WHERE pa.jugador = '$idUsuario'
        )
        ORDER BY diferencia_aciertos ASC
        LIMIT 1";

        $preguntas = $this->database->query($queryPregunta);
        return $preguntas;
    }

    private function obtenerPorcentajeAciertos($idUsuario)
    {
        $query = "
        SELECT 
            COUNT(*) as partidas_jugadas,
            SUM(puntaje) as puntaje_total
        FROM partida
        WHERE jugador = '$idUsuario'
    ";
        $result = $this->database->query($query);

        if ($result[0]['partidas_jugadas'] > 0) {
            $partidasJugadas = $result[0]['partidas_jugadas'];
            $puntajeTotal = $result[0]['puntaje_total'];

            $respuestasCorrectas = $puntajeTotal;

            return ($respuestasCorrectas / ($partidasJugadas * 1.0)) * 100;
        } else {
            return 0;
        }
    }

    private function actualizarEstadisticasPregunta($preguntaId, $esCorrecta)
    {
        try {
            if ($esCorrecta) {
                $queryUpdate = "UPDATE pregunta 
                            SET veces_que_salio = veces_que_salio + 1, 
                                veces_correcta = veces_correcta + 1, 
                                ultima_vez_que_salio = CURRENT_TIMESTAMP
                            WHERE id = '$preguntaId'";
            } else {
                $queryUpdate = "UPDATE pregunta 
                            SET veces_que_salio = veces_que_salio + 1, 
                                ultima_vez_que_salio = CURRENT_TIMESTAMP
                            WHERE id = '$preguntaId'";
            }
            $this->database->execute($queryUpdate);

            $queryPorcentajeDificultad = "UPDATE pregunta 
                                      SET dificultad = ((veces_que_salio - veces_correcta) / veces_que_salio) * 100 
                                      WHERE id = '$preguntaId'";
            $this->database->execute($queryPorcentajeDificultad);

        } catch (Exception $e) {
            echo "Error al actualizar las estadísticas: " . $e->getMessage();
        }
    }

    private function getCantidadToken($usuario)
    {
        $query = "SELECT token FROM usuario WHERE id= '$usuario'";
        $result = $this->database->execute($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return (int) $row['token'];
        } else {
            return 0;
        }
    }


}
