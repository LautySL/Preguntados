<?php

class EdicionController
{


    private $model;
    private $Presenter;

    public function __construct($Model, $Presenter)
    {
        $this->model = $Model;
        $this->Presenter = $Presenter;
    }



    public function get()
    {

        $datos=$this->model->getReportedQuestions();

        $this->Presenter->render("view/vistaEditor.mustache",$datos);


    }


}