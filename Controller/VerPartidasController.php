<?php

class VerPartidasController
{
    private $model;
    private $presenter;

    public function __construct($model, $presenter)
    {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get($pagina = 1)
    {
        $usuarioId = $_SESSION['id_usuario']; // Asume que el ID del usuario está en la sesión
        $partidas = $this->model->getPartidasConPreguntas($usuarioId, $pagina);
        echo $this->presenter->render("view/Partidas.mustache", ['partidas' => $partidas]);
    }
}