<?php
class AdminController
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
        $this->checkLoggedIn();
        $this->presenter->render("view/home/get");
    }

    private function request($metodo)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dateFrom = $_POST['dateFrom'] ?? '';
            $dateTo = $_POST['dateTo'] ?? '';

            if (!empty($dateFrom) && !empty($dateTo)) {
                try {
                    $data = call_user_func([$this->model, $metodo], $dateFrom, $dateTo);
                    $filename = isset($data['filename']) ? $data['filename'] : '';
                    $this->presenter->render('view/presentarDatos.mustache', [
                        $metodo => $filename,
                    ]);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            }else{
                $this->presenter->render("view/presentarDatos.mustache");
            }
        }else{
            $this->presenter->render("view/presentarDatos.mustache");
        }
    }

    public function totalJugadores() {
        $this->request('totalJugadores');
    }

    public function totalPartidas()
    {
        $this->request('totalPartidas');
    }

    public function totalPreguntas()
    {
        $this->request('totalPreguntas');
    }
    public function totalPreguntasCreadas()
    {
        $this->request('totalPreguntasCreadas');    
    }

    public function usuariosNuevos()
    {
        $this->request('usuariosNuevos');
    }

    public function totalCorrectas()
    {
        $this->request('totalCorrectas');
    }

    public function totalUsuariosPorPais()
    {
        $this->request('totalUsuariosPorPais');
    }

    public function totalUsuariosPorSexo()
    {
        $this->request('totalUsuariosPorSexo');
    }

    public function totalUsuariosPorRango()
    {
        $this->request('totalUsuariosPorRango');
    }

    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: /');
            exit();
        }
    }
}
