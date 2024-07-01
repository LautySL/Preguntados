<?php

class RegistroController
{
    private $presenter;
    private $model;
    private $location;

    public function __construct($Model, $Presenter, $location)
    {
        $this->model = $Model;
        $this->presenter = $Presenter;
        $this->location = $location;
    }

    public function get()
    {

        $mensaje = isset($_SESSION['mensaje']) ? $_SESSION['mensaje'] : null;
        $exito = isset($_SESSION['exito']) ? $_SESSION['exito'] : null;

        unset($_SESSION['mensaje']);
        unset($_SESSION['exito']);
        $data = [
            'mensaje' => $mensaje,
            'exito' => $exito,
        ];

        $this->presenter->render("view/register_form.mustache", $data);
    }

    public function insertar()
    {
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $ano_de_nacimiento = $_POST['ano_de_nacimiento'] ?? 0;
        $sexo = $_POST['sexo'] ?? '';
        $mail = $_POST['mail'] ?? '';
        $contrasena = $_POST['contrasena'] ?? '';
        $nombre_de_usuario = $_POST['nombre_de_usuario'] ?? '';
        $latitud = $_POST['latitud'] ?? '';
        $longitud = $_POST['longitud'] ?? '';

        $datosConCoordenadas = $this->location->obtenerCiudadYPais($latitud, $longitud);
        $pais = $datosConCoordenadas['pais'];
        $ciudad = $datosConCoordenadas['ciudad'];

        // Manejo de la foto de perfil
        $archivo_nombre = $this->model->verificarQueHayaFoto();
        if ($archivo_nombre != "fotoGenerica.png" && isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] == UPLOAD_ERR_OK) {
            $archivo_temporal = $_FILES['foto_de_perfil']['tmp_name'];
            $directorio_destino = 'public/img/fotoPerfil'; // Asegúrate de que esta ruta es correcta


            $ruta_destino = $directorio_destino . '/' . $archivo_nombre;

            if (move_uploaded_file($archivo_temporal, $ruta_destino)) {
                // Archivo subido con éxito
            } else {
                $_SESSION['mensaje'] = "Error al subir la foto de perfil.";
                $_SESSION['exito'] = false;
                header('Location: /registro');
                exit();
            }
        } else {
            $archivo_nombre = "fotoGenerica.png"; // Asegurarse de que haya una foto por defecto
        }
        try {
            $hash_activacion = md5(uniqid(rand(), true));
            $this->model->registrarJugador($nombre_de_usuario, $contrasena, $nombre, $apellido, $ano_de_nacimiento, $sexo, $mail, $archivo_nombre, $pais, $ciudad, $hash_activacion, $latitud, $longitud);
            $this->model->enviarCorreoActivacion($mail, $nombre, $hash_activacion);


            $_SESSION['mensaje'] = "Registro exitoso. Por favor, revisa tu correo para activar tu cuenta.";
            $_SESSION['exito'] = true;
        } catch (Exception $e) {
            $_SESSION['mensaje'] = "Hubo un error en el registro. Por favor, intenta nuevamente.";
            $_SESSION['exito'] = false;
        }
        header('Location: /registro/');
        exit();
    }
}