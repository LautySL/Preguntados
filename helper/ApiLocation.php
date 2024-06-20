<?php

class ApiLocation{
 private $apiKey;

    public function __construct($apiKey) {
    $this->apiKey = $apiKey;
}

    public function obtenerCiudadYPais($latitud, $longitud) {
        $url = "https://us1.locationiq.com/v1/reverse.php?key={$this->apiKey}&lat={$latitud}&lon={$longitud}&format=json";

        $response = file_get_contents($url);
        if ($response === FALSE) {
            return array('error' => 'Error al hacer la solicitud a la API.');
        }

        $data = json_decode($response, true);

        $ciudad = null;
        $pais = null;

        // Verificamos si existen direcciones disponibles
        if (isset($data['address'])) {
            // Intentamos obtener la ciudad desde el nivel más específico
            if (isset($data['address']['city'])) {
                $ciudad = $data['address']['city'];
            } elseif (isset($data['address']['town'])) {
                $ciudad = $data['address']['town'];
            } elseif (isset($data['address']['village'])) {
                $ciudad = $data['address']['village'];
            } elseif (isset($data['address']['hamlet'])) {
                $ciudad = $data['address']['hamlet'];
            } elseif (isset($data['address']['suburb'])) {
                $ciudad = $data['address']['suburb'];
            }

            // Obtener el país
            if (isset($data['address']['country'])) {
                $pais = $data['address']['country'];
            }

            // Si no se ha podido determinar la ciudad, se puede establecer como "Desconocida"
            if (empty($ciudad)) {
                $ciudad = 'Desconocida';
            }
        } else {
            return array('error' => 'No se encontraron datos de dirección en la respuesta.');
        }

        return array('ciudad' => $ciudad, 'pais' => $pais);
    }
}

