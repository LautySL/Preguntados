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

    if (isset($data['address'])) {

        if (isset($data['address']['city'])) {
            $ciudad = $data['address']['city'];
        }

        if (isset($data['address']['country'])) {
            $pais = $data['address']['country'];
        }
    }
    return array('ciudad' => $ciudad, 'pais' => $pais);
}
}

