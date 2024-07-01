<?php

namespace App\Controller;

class PagoExitosoControllerController {

    private $presenter;

    public function __construct($Presenter) {
        $this->presenter = $Presenter;
    }

    public function mostrarPagoPendiente() {
        $this->presenter->render("view/pago_exitoso.mustache");
    }
}
?>