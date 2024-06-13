<?php


class HomeController
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
         $templateData = $this->contextoParaPasarALaVista();

        if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST["usuario"])) {
            $idUsuario = $_POST["id"];
            $tipoUsuario = $this->model->obtenerTipoUsuario($idUsuario);
            

            $templateData["esJugador"] = ($tipoUsuario == 'esJugador');
            $templateData["esEditor"] = ($tipoUsuario == 'esEditor');
            $templateData["esAdministrador"] = ($tipoUsuario == 'esAdministrador');
        }

        $this->presenter->render("view/Home.mustache",$templateData);
    }


    private function contextoParaPasarALaVista(): array
    {
        $usuario = $_SESSION['usuario'] ?? null;
        $error = $_SESSION["error_login"] ?? null;

        $templateData = [
            "error" => $error
        ];

        if ($usuario !== null) {
            $templateData["usuario"] = $usuario;
        }

        return $templateData;
    }
}