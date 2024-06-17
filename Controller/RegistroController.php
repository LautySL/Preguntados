<?php


class RegistroController
{
    private $presenter;
    private $model;

    public function __construct($Model, $Presenter)
    {
        $this->model = $Model;
        $this->presenter = $Presenter;
    }

    public function get()
    {
        $this->presenter->render("view/register_form.mustache");
    }

    public function insertar()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $ano_de_nacimiento = $_POST['ano_de_nacimiento'] ?? 0;
            $sexo = $_POST['sexo'] ?? '';
            $pais =  $_POST['pais'] ?? '';
            $ciudad =  $_POST['ciudad'] ?? '';
            $mail = $_POST['mail'] ?? '';
            $contrasena = $_POST['contrasena'] ?? '';
            $nombre_de_usuario = $_POST['nombre_de_usuario'] ?? '';
            $latitud = $_POST['latitud'] ?? '';
            $longitud = $_POST['longitud'] ?? '';
            $fecha_creacion = 'CURRENTDATE';

            if (isset($_FILES['foto_de_perfil'])) {
                $archivo_nombre = $_FILES['foto_de_perfil']['name'];
                $archivo_temporal = $_FILES['foto_de_perfil']['tmp_name'];
                $archivo_tamaño = $_FILES['foto_de_perfil']['size'];
                $archivo_error = $_FILES['foto_de_perfil']['error'];
                $directorio_destino = 'public/img/fotoPerfil/';

                $ruta_destino = $directorio_destino . $archivo_nombre;
                move_uploaded_file($archivo_temporal, $ruta_destino);
                //} else {
                //Si no se proporciona una nueva imagen, conserva la imagen existente
                //$ruta_destino = $directorio_destino . $_FILES['foto_de_perfil']['name'];
            }


            if (!empty($foto_de_perfil)) {
                move_uploaded_file($_FILES['foto_de_perfil']['tmp_name'], 'public/img/fotoPerfil/' . $archivo_nombre);
            }
            $hash_activacion = md5(uniqid(rand(), true));
            $this->model->enviarCorreoActivacion($mail, $nombre, $hash_activacion);
            $this->model->registrarJugador($nombre_de_usuario, $contrasena, $nombre, $apellido, $ano_de_nacimiento, $sexo, $mail, $foto_de_perfil, $pais, $ciudad, $hash_activacion, $latitud, $longitud, $fecha_creacion);
        }

        header('Location: /home/get');
        exit();
    }
}
