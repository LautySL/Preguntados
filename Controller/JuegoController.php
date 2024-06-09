<?php

class JuegoController
{
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter)
    {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }

    public function get()
    {
        $this->checkLoggedIn();
        $this->filtroAntiF5();
        $data = $this->obtenerDataParaPartida();
        $this->presenter->render("View/lobby.mustache", $data);
    }

    public function verificarRespuesta()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $respuestaId = $_POST['respuesta_id'];
            $preguntaId=$_POST['pregunta_id'];
            $idPartida = $_SESSION['id_partida'];
            $esCorrecta = $this->model->verificarYGuardarRespuesta($idPartida, $preguntaId, $respuestaId);

            if ($esCorrecta) {
                $this->continuaJugando();
            } else {
                $this->gameOver();
                exit;
            }
        }
    }

    public function iniciarPartida()
    {
        // Limpiar todas las variables de sesión relacionadas con el juego
        unset($_SESSION['pagina_cargada']);
        unset($_SESSION['puntaje']);
        unset($_SESSION['puntaje_final']);

        $idUsuario = $_SESSION['id_usuario'];
        $idPartida = $this->model->crearPartida($idUsuario);
        $_SESSION['id_partida'] = $idPartida;
        header("Location: index.php?controller=Juego&action=get");
        exit();
    }

    private function gameOver()
    {
        $_SESSION['puntaje_final'] =$_SESSION['puntaje'] ?? 0;
        $_SESSION['puntaje'] = 0;
        unset($_SESSION['pagina_cargada']);
        header("Location: index.php?controller=Juego&action=get&finalizado=true");
        exit;
    }

    private function continuaJugando()
    {
        $_SESSION['puntaje'] = isset($_SESSION['puntaje']) ? $_SESSION['puntaje'] + 1 : 1;
        $this->model->actualizarPuntajeFinal( $_SESSION['id_partida'],$_SESSION['puntaje']);
        unset($_SESSION['pagina_cargada']);
        header('Location: index.php?controller=Juego&action=get');
        exit;
    }

    private function obtenerDataParaPartida(): array
    {
        $_SESSION['pagina_cargada'] = true;
        $nombreUsuario = $_SESSION['usuario'];
        $puntaje = $_SESSION['puntaje'] ?? 0;
        $finalizado = isset($_GET['finalizado']) && $_GET['finalizado'] == 'true';
        $puntajeFinal = $_SESSION['puntaje_final'] ?? null;
        return $this->model->obtenerDataParaPartida($_SESSION['id_usuario'], $puntaje, $finalizado, $puntajeFinal);
    }

    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php');
            exit();
        }
    }

    private function filtroAntiF5()
    {
        if (isset($_SESSION['pagina_cargada']) && !isset($_GET['finalizado'])) {
            $this->gameOver();
            exit;
        }
    }
}