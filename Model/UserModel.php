<?php


class UserModel
{
    private $database;
    private $mail;
    public function __construct($database,$mail)
    {
        $this->database = $database;
        $this->mail =$mail;
    }

    public function registrarJugador($nombre, $apellido, $ano_de_nacimiento, $sexo, $mail, $contrasena, $nombre_de_usuario, $foto_de_perfil, $hash_activacion)
    {

        $sql = "INSERT INTO usuario (nombre_de_usuario, contrasena, nombre, apellido, ano_de_nacimiento, sexo, mail, foto_de_perfil, pais, ciudad, cuenta_verificada, hash_activacion)
                   VALUES ('$nombre_de_usuario', '$contrasena', '$nombre', '$apellido', '$ano_de_nacimiento', '$sexo', '$mail', '$foto_de_perfil', '...', '..', FALSE, '$hash_activacion')";
        
        $this->database->execute($sql);


        $idJugador = $this->database->getLastInsertId();
        $sqlJugador = "INSERT INTO jugador (id) VALUES ($idJugador)";

        $this->database->execute($sqlJugador);
    }

    public function LogInconsulta($usuario, $password)
    {

        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario' AND contrasena= '$password'";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1 /*&& $this->emailVerificado()*/) {
            $usuario = $result->fetch_assoc();

            $_SESSION["usuario"] = $usuario["nombre_de_usuario"];
            $_SESSION['id_usuario'] = $usuario['id_usuario'];

            return true;
        } else {
            return false;
        }
    }
    private function emailVerificado($hash_activacion){
    if (isset($hash_activacion)) {
        // Buscar usuario por código de activación en la base de datos
        $usuario = $this->database->getUsuarioPorCodigoActivacion($hash_activacion);

        if ($usuario) {
            if (!$usuario['activado']) {
                // Activar cuenta en la base de datos
                $this->database->activarUsuario($usuario['id']);
                return true; // Cuenta activada correctamente
            } else {
                return false; // La cuenta ya está activada
            }
        } else {
            return false; // Código de activación inválido
        }
    } else {
        return false; // No se proporcionó un código en la URL
    }

    }

    
    function enviarCorreoActivacion($email, $nombre, $hash_activacion)
{


    try {
        // Configurar servidor SMTP
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.office365.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'correoverificador2023@hotmail.com';
        $this->mail->Password = 'admin2023';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;

        // Configurar remitente y destinatario
        $this->mail->setFrom('correoverificador2023@hotmail.com', 'Admin');
        $this->mail->addAddress($email, $nombre);

        // Configurar contenido del correo
        $this->mail->isHTML(true);
        $this->mail->Subject = 'Activación de cuenta';
        $this->mail->Body    = "Hola $nombre,<br><br>Por favor haz clic en el siguiente enlace para activar tu cuenta:<br>";
        $this->mail->Body    .= "<a href='http://localhost/index.php?controller=activacion&method=activar&codigo=$hash_activacion'>Activar cuenta</a>";

        $this->mail->send();
        echo 'El correo electrónico de activación se ha enviado correctamente.';
    } catch (Exception $e) {
        echo "Error al enviar el correo electrónico: {$this->mail->ErrorInfo}";
    }
}


    public function verPerfil()
    {
        $usuario = $_SESSION["usuario"];
        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario'";
        $resultado = $this->database->query($sql);
        $_SESSION['perfil'] = $resultado;
        return $resultado;
    }
}