<?php

class RankingController
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

        $rankingList =$this->model->obtenerRankingJugadores();

        $this->presenter->render("view/Ranking.mustache",['rankingList' => $rankingList]);

    }
}