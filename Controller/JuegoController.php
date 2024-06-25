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
        $data = $this->filtroAntiF5();
        $this->presenter->render("view/lobby.mustache", $data);
    }

    public function verificarRespuesta()
    {
            $respuestaId = $_POST['respuesta_id'];
            $preguntaId=$_POST['pregunta_id'];
            $idPartida = $_SESSION['id_partida'];
            $puntaje = $_SESSION['puntaje'] ?? 0;
            $esCorrecta=false;

        if($_SESSION['token_usado']){
            $respuestaId = $this->model->obtenerRespuestaCorrectaId($preguntaId);
            unset($_SESSION['token_usado']);
        }
        if($preguntaId == $_SESSION['data']['pregunta_id']){
            $esCorrecta = $this->model->procesarRespuesta($idPartida, $preguntaId, $respuestaId, $puntaje);
        }
        if (!$esCorrecta) {
            $_SESSION['finalizado']= true;
            }

        unset ($_SESSION['start_time']);
        unset($_SESSION['flag-partida']);
        header('Location: /juego/get');


    }

    public function iniciarPartida()
    {
        unset ($_SESSION['start_time']);
        unset($_SESSION['flag-partida']);
        unset($_SESSION['puntaje']);
        unset($_SESSION['puntaje_final']);
        unset ($_SESSION['finalizado']) ;

        $idUsuario = $_SESSION['id_usuario'] ;

        $idPartida = $this->model->crearPartida($idUsuario);
        $_SESSION['id_partida'] = $idPartida;
        header("Location: /juego/get");
        exit();
    }

    public function reportarPregunta(){
        $preguntaId=$_POST['pregunta_id'];
        $idUsuario=$_SESSION['id_usuario'];
        $_SESSION['reportada']=true;
        $this->model->reportarPregunta($preguntaId, $idUsuario);
        header("Location: /juego/get");
    }

    public function usarToken(){


       $usertienetokens =$this->model->verificarSiTieneTokenYusarlo( $_SESSION['id_usuario']);

        if($usertienetokens) {
            $_SESSION['token_usado'] = $usertienetokens;
            $this->verificarRespuesta();
        }
        else{
            $this->get();
        }
    }


    private function obtenerDataParaPartida(): array
    {
        $_SESSION['flag-partida'] = true;
        $puntaje = $_SESSION['puntaje'] ?? 0;
        $finalizado = $_SESSION['finalizado'] ?? null;
        return $this->model->obtenerDataParaPartida($_SESSION['id_usuario'], $puntaje, $finalizado, $puntaje);
    }


    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: /');
            exit();
        }
    }

    private function filtroAntiF5()
    {
        if (isset($_SESSION['flag-partida'])) {

            $_SESSION['data']['time_left'] = $this->model->getTimeLeft();
            $_SESSION['data']['reportada']= $_SESSION['reportada'];
            return $_SESSION['data'];
        }
        else{
            $data = $this->obtenerDataParaPartida();
            $_SESSION['start_time']=time();
            $_SESSION['reportada'] = false;
            $_SESSION['data']= $data;
            return $data;
            }

        }



}