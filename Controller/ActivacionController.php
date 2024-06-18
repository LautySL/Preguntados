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
        try {
            $codigo = $_GET['codigo'];

            $verificado = $this->model->emailVerificado($codigo);

            if ($verificado) {
                // Redirigir al index si el código es válido
                $_SESSION['mensajeCuentaActivada'] = "¡Tu cuenta ha sido activada correctamente!";
                $_SESSION['cuentaActivada'] = true;
                header("Location: /");
                exit();
            } else {
                throw new Exception("Código de verificación no válido.");
            }
        } catch (Exception $e) {
            // Capturar la excepción y mostrar el mensaje de error
            $_SESSION['mensajeCuentaActivada'] = $e->getMessage();
            $_SESSION['cuentaActivada'] = false;
            header("Location: /");
            exit();
        }
    }

}