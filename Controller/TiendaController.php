<?php

class TiendaController
{
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter) {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }

    public function get()
    {

        $this->presenter->render("view/tienda.mustache");
    }

    public function comprar(){

      //  $this->model->ProcesarCompra();
        header("Location:tienda/get");
    }

}