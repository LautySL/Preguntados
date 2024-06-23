<?php
class VerPerfilPropioController
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
        $perfil = $this->model->verPerfilPropio();
        if (!empty($perfil)) {
            $userId = $_SESSION['id_usuario'];  // Obtener el ID del usuario desde la sesión
            $puntajeTotal = $this->model->obtenerPuntajeTotal($userId);
            $perfil[0]['puntaje_total'] = $puntajeTotal;
            $this->presenter->render('view/Perfil.mustache', $perfil[0]);
        } else {
            echo "upsi, algo falla";
        }
    }
}