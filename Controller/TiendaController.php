<?php

namespace App\Controller;

use Exception;

class TiendaController {

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

    public function comprar() {
        try {
            // Configurar las credenciales de Mercado Pago
            $accessToken = "APP_USR";

            // Crear una preferencia de pago
            $preference = $this->crearPreferencia($accessToken);

            // Verificar si se creó la preferencia correctamente
            if ($preference && isset($preference['id'])) {
                // Obtener la URL de inicio de la preferencia
                $initPoint = $preference['init_point'];

                // Redirigir al usuario a la página de pago de Mercado Pago
                header('Location: ' . $initPoint);

                exit();
            } else {
                echo "Error al crear la preferencia de pago.";
            }
        } catch (Exception $e) {
            // Manejar cualquier excepción general
            echo "Error general: " . $e->getMessage();
        }
    }

    private function crearPreferencia($accessToken) {
        // URL para crear la preferencia en Mercado Pago
        $url = 'https://api.mercadopago.com/checkout/preferences';

        // Configurar los datos del ítem (en este caso, una mora)
        $item = array(
            "title" => "Mora 🍇",
            "quantity" => 1,
            "unit_price" => 10.00 // Precio de una mora
        );

        // Datos para la preferencia
        $preferencia = array(
            "items" => array($item),
            "back_urls" => array(
                "success" => "http://localhost/pago_exitoso",
                "failure" => "http://localhost/pago_rechazado",
                "pending" => "http://localhost/pago_pendiente"
            ),
            "auto_return" => "approved"
        );

        // Configurar la solicitud HTTP
        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: application/json\r\n" .
                    "Authorization: Bearer $accessToken",
                'content' => json_encode($preferencia)
            )
        );

        // Crear el contexto de la solicitud
        $context  = stream_context_create($options);

        // Realizar la solicitud HTTP y obtener la respuesta
        $result = file_get_contents($url, false, $context);

        // Manejar errores si la respuesta no es válida
        if ($result === false) {
            throw new Exception("Error al crear la preferencia de pago en Mercado Pago");
        }

        // Decodificar la respuesta JSON
        $response = json_decode($result, true);

        return $response;
    }

    public function success() {
        try {

            // Verificar si el usuario está autenticado
            if (!isset($_SESSION['id_usuario'])) {
                throw new Exception("Usuario no autenticado");
            }

            $userId = $_SESSION['id_usuario']; // Obtener el ID del usuario desde la sesión
            $cantidadMoras = 1; // Cantidad de moras que se añadirán al usuario (en este caso, 1)

            // Actualizar los tokens del usuario en la base de datos
            $this->model->actualizarTokens($userId, $cantidadMoras);

            // Redirigir al usuario a la página de éxito
            header('Location: http://localhost/pago_exitoso'); // Ajusta la URL según corresponda
            exit();
        } catch (Exception $e) {
            // Manejar cualquier excepción que pueda ocurrir durante el proceso
            echo "Error al procesar la compra: " . $e->getMessage();
        }
    }
}
?>