<?php


class PagoExitosoController {

    private $presenter;

    public function __construct($Presenter) {
        $this->presenter = $Presenter;
    }

    public function get() {
        $this->presenter->render("view/pago_exitoso.mustache");
    }
}
?>