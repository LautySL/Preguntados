<?php

class HomeController {
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter) {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }

    public function get() {
        $templateData = $this->contextoParaPasarALaVista();
        $this->presenter->render("view/Home.mustache", $templateData);
    }

    private function contextoParaPasarALaVista(): array {
        $usuario = $_SESSION['usuario'] ?? null;
        $error = $_SESSION["error_login"] ?? null;
        $tipo = $_SESSION["tipo_cuenta"] ?? null;
        $iduser = $_SESSION['id_usuario'] ?? null;

        $templateData = [
            "error" => $error
        ];

        if (isset($_SESSION['mensajeCuentaActivada']) && isset($_SESSION['cuentaActivada'])) {
            $templateData['mensajeCuentaActivada'] = $_SESSION['mensajeCuentaActivada'];
            $templateData['cuentaActivada'] = $_SESSION['cuentaActivada'];

            unset($_SESSION['mensajeCuentaActivada']);
            unset($_SESSION['cuentaActivada']);
        }

        if (isset($_SESSION['mensajePregunta'])) {
            $templateData['mensajePregunta'] = $_SESSION['mensajePregunta'];
            unset($_SESSION['mensajePregunta']);
        }

        if ($usuario !== null) {
            $puntaje = $this->model->getMaxPuntaje($iduser);
            $ultimasPartidas = $this->model->getUltimasPartidas($iduser, 5);
            $fotoHeader = $this->model->obtenerFotoPerfil($iduser);

            $templateData = array_merge($templateData, [
                'foto-de-perfil' => $fotoHeader,
                'puntuacion' => $puntaje,
                'usuario' => $_SESSION['usuario'] ?? null,
                'tipoCuenta' => $_SESSION["tipo_cuenta"] ?? null,
                'isJugador' => $tipo['esJugador'],
                'isEditor' => $tipo['esEditor'],
                'isAdministrador' => $tipo['esAdministrador'],
                'partidasUsuario' => $ultimasPartidas,
                'token' => $this->model->getToken($iduser) ?? null
            ]);
        }

        return $templateData;
    }
}