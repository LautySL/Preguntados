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

    public function get()
    {
        $usuarioId = $_SESSION['id_usuario'];
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $partidas = $this->model->getPartidasConPreguntas($usuarioId, $page);
        $totalPartidas = $this->model->getTotalPartidas($usuarioId);
        $partidasPorPagina = 5;
        $totalPaginas = ceil($totalPartidas / $partidasPorPagina);

        $data = [
            'partidas' => $partidas,
            'totalPaginas' => $totalPaginas,
            'paginaActual' => $page
        ];

        $this->presenter->render('view/Partidas.mustache', $data);
    }
}