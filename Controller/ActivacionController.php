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

    // Configuración del enrutador
    public static function getRouter()
    {
        return new Router(
            "getHomeController", "get");
    }


    public function activar(){
        if (isset($_GET['codigo'])) {
            $codigo = $_GET['codigo'];

            // Verificar el código de activación
            if ($this->model->emailVerificado($codigo)) {
                header('Location:index.php?controller=home&action=get');
            } else {
                // Redireccionar o mostrar mensaje de error
            }
        } else {
            // Redireccionar o mostrar mensaje de error
        }
    }

}