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
        $_SESSION['perfil_ajeno_id'] = $_POST['id'];
        $datosPerfil = $this->model->VerPerfilAjeno();
        if ($datosPerfil) {
            $this->presenter->render("view/PerfilAjeno.mustache", $datosPerfil);
        } else {
            echo "Error: No se pudieron obtener los datos del perfil.";
        }
    }
}