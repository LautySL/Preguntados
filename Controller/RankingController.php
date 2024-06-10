<?php

require_once 'helper/MustachePresenter.php';
require_once 'Model/UserModel.php';

class RankingController {
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter) {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }

    public function get() {
        $rankingData = $this->model->getRankingData();
        $this->presenter->render("view/Ranking.mustache", ['players' => $rankingData]);
    }
}