<?php
class VerPerfilAjenoController
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

        $userId =  htmlspecialchars($_GET['user']);
        $datosPerfil = $this->model->VerPerfilAjeno($userId);

        if ($datosPerfil) {
            $this->presenter->render("view/PerfilAjeno.mustache", $datosPerfil);
        } else {
            echo "Error: No se pudieron obtener los datos del perfil.";
        }
    }
}

