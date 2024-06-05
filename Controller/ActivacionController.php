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

    public function get()
    {
        $codigo = isset($_GET['codigo']) ? htmlspecialchars($_GET['codigo']) : '';


        $datos = array(
            'codigo' => $codigo
        );

        $this->presenter->render("view/activar.mustache",$datos);
    }

    public function activar()
    {

        $codigo =$_GET['codigo'];
        var_dump($codigo);
        $verificado = $this->model->emailVerificado($codigo);
        if ($verificado) {
            // Redirigir al index
            header("Location: index.php");
            exit();
        } else {
            // Mostrar error
            echo "Error: Código de verificación no válido.";
        }

    }

}