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
        $tipo= $_SESSION["tipo_cuenta"]?? null;
        
        $templateData = [
            "error" => $error
        ];

        if ($usuario !== null) {
            $templateData=[
                'usuario'=> $_SESSION['usuario'] ?? null,
                 'tipoCuenta'=>$_SESSION["tipo_cuenta"]?? null,
                'isJugador' =>$tipo['esJugador'],
                'isEditor' =>$tipo['esEditor'],
                'isAdministrador'=>$tipo['esAdministrador']



            ];
        }

        return $templateData;
    }
}