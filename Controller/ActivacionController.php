<?php
class ActivacionController
{
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter)
    {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }



    public function activar(){
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            // Verificar el código de activación
            if ($this->model->emailVerificado($codigo)) {
                header('Location:index.php?');
            } else {
                header('Location:index.php?');
            }
        } else {
            header('Location:index.php?');
        }
    }

}