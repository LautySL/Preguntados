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