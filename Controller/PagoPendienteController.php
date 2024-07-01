<?php
class PagoPendienteController {

    private $presenter;

    public function __construct($Presenter) {
        $this->presenter = $Presenter;
    }

    public function mostrarPagoPendiente() {
        $this->presenter->render("view/pago_pendiente.mustache");
    }
}
?>