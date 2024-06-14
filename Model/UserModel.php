<?php


class UserModel
{
    private $database;
    private $mail;
    public function __construct($database, $mail)
    {
        $this->database = $database;
        $this->mail = $mail;
    }

    public function registrarJugador($nombre_de_usuario, $contrasena, $nombre, $apellido, $ano_de_nacimiento, $sexo, $mail, $foto_de_perfil, $pais, $ciudad, $hash_activacion, $latitud, $longitud)
    {

        $sql = "INSERT INTO usuario (nombre_de_usuario, contrasena, nombre, apellido, ano_de_nacimiento, sexo, mail, foto_de_perfil, pais, ciudad, cuenta_verificada, hash_activacion, latitud, longitud)
                   VALUES ('$nombre_de_usuario', '$contrasena', '$nombre', '$apellido', '$ano_de_nacimiento', '$sexo', '$mail', '$foto_de_perfil', '$pais', '$ciudad', FALSE, '$hash_activacion', '$latitud', '$longitud')";

        $this->database->execute($sql);


        $idJugador = $this->database->getLastInsertId();
        $sqlJugador = "INSERT INTO jugador (id) VALUES ($idJugador)";

        $this->database->execute($sqlJugador);
    }

    public function LogInconsulta($usuario, $password)
    {

        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario' AND contrasena= '$password' AND cuenta_verificada = 1";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1) {
            $usuario = $result->fetch_assoc();

            $_SESSION["usuario"] = $usuario["nombre_de_usuario"];
            $_SESSION['id_usuario'] = $usuario['id'];
            $tipoUsuario = $this->obtenerTipoUsuario($usuario['id']);

            $_SESSION['tipo_cuenta'] =[
                "esJugador" => ($tipoUsuario == 'esJugador'),
                "esEditor" => ($tipoUsuario == 'esEditor'),
                "esAdministrador" => ($tipoUsuario == 'esAdministrador'),
            ];

            return true;
        } else {
            return false;
        }
    }
    public function emailVerificado($hash_activacion)
    {


        $query = "SELECT * FROM usuario WHERE hash_activacion = '$hash_activacion'";
        $result = $this->database->execute($query);
        $usuario = $result->fetch_assoc();


        if (!$usuario['activado']) {
            // Actualizar el estado de activación del usuario en la base de datos
            $idUsuario = $usuario['id'];
            $updateQuery = "UPDATE usuario SET cuenta_verificada = 1 WHERE id = $idUsuario";
            $success = $this->database->execute($updateQuery);
            if ($success) {

                return true; //
            } else {
                return false;
            }

        }
    }

    public function enviarCorreoActivacion($email, $nombre, $hash_activacion)
    {
        try {
            $configMail = Configuration::getConfigMail();

            // Configurar servidor SMTP
            $this->mail->isSMTP();
            $this->mail->Host = $configMail['Host'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $configMail['Username'];
            $this->mail->Password = $configMail['Password'];
            $this->mail->SMTPSecure = $configMail['SMTPSecure'];
            $this->mail->Port = $configMail['Port'];

            // Configurar remitente y destinatario
            $this->mail->setFrom($configMail['Username'], 'Admin');
            $this->mail->addAddress($email, $nombre);
            $this->mail->CharSet = 'UTF-8';

            // Configurar contenido del correo
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Activación de Cuenta';
            $this->getBodyMail($nombre, $hash_activacion);

            $this->mail->send();
            echo 'El correo electrónico de activación se ha enviado correctamente.';
        } catch (Exception $e) {
            echo "Error al enviar el correo electrónico: {$this->mail->ErrorInfo}";
        }
    }

    public function getRankingData()
    {
        $sql = "SELECT u.id, u.nombre_de_usuario, SUM(puntaje) AS max_puntaje
        FROM usuario u
        JOIN jugador j ON u.id = j.id
        JOIN partida p ON j.id = p.jugador
        GROUP BY u.id, u.nombre_de_usuario
        ORDER BY max_puntaje DESC";

        $result = $this->database->execute($sql);

        $rankingData = array();
        while ($row = $result->fetch_assoc()) {
            $rankingData[] = $row;
        }

        return $rankingData;
    }

    public function verPerfilPropio()
    {
        $usuario = $_SESSION["usuario"];
        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario'";
        $resultado = $this->database->query($sql);
        $_SESSION['perfil'] = $resultado;
        return $resultado;
    }

    public function VerPerfilAjeno()
    {
        if (isset($_SESSION['perfil_ajeno_id'])) {
            $id = $_SESSION['perfil_ajeno_id'];
            $sql = "SELECT * FROM usuario WHERE id = '$id'";
            $result = $this->database->query($sql);

            if ($result && count($result) > 0) {
                $datosPerfil = $result[0]; // Obtenemos el primer (y único) resultado
                return $datosPerfil;
            } else {
                echo "No se encontraron datos para el ID: " . $id;
                return null;
            }
        } else {
            echo "ID de perfil ajeno no está seteado en la sesión.";
            return null;
        }
    }
    public function getMaxPuntaje($usuario)
    {
        $query = "SELECT MAX(puntaje) AS max_puntaje FROM partida WHERE jugador=$usuario";

        $resultado = $this->database->query($query);

        if ($resultado && isset($resultado[0]['max_puntaje'])) {
            // Convertir el valor de "max_puntaje" a entero y devolverlo
            return (int) $resultado[0]['max_puntaje'];
        } else {
            return 0;
        }


    }


    private function obtenerTipoUsuario($idUsuario) {
    
    $query = "SELECT 
                CASE
                    WHEN j.id IS NOT NULL THEN 'esJugador'
                    WHEN e.id IS NOT NULL THEN 'esEditor'
                    WHEN a.id IS NOT NULL THEN 'esAdministrador'
                    ELSE ''
                END AS tipoUsuario
            FROM usuario u
            LEFT JOIN jugador j ON u.id = j.id
            LEFT JOIN editor e ON u.id = e.id
            LEFT JOIN administrador a ON u.id = a.id
            WHERE u.id = $idUsuario";

    $result = $this->database->execute($query);
    // $result = mysqli_query($conexion, $query); // Suponiendo que estás utilizando MySQLi

    $tipoUsuario = mysqli_fetch_assoc($result)['tipoUsuario'];

    return $tipoUsuario;
    
    }



    private function getBodyMail($nombre, $hash_activacion)
    {
        $this->mail->Body = "
<!DOCTYPE html>

<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        .header {
            background: #4CAF50;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            font-size: 16px;
            color: #ffffff;
            background: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>Activación de Cuenta</h1>
        </div>
        <div class='content'>
            <p>Hola $nombre,</p>
            <p>¡Gracias por registrarte en nuestro sitio! Para completar el proceso de registro, por favor haz clic en el siguiente enlace para activar tu cuenta:</p>
            <p><a class='button' href='http://localhost/index.php?controller=activacion&method=get&codigo=$hash_activacion'>Activar Cuenta</a></p>
            <p>Si tienes alguna pregunta, no dudes en contactarnos.</p>
            <p>Saludos cordiales,<br>El equipo de soporte</p>
        </div>
        <div class='footer'>
            <p>Este es un correo electrónico generado automáticamente, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>
";
    }
}