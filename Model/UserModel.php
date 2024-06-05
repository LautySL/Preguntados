<?
include ("vendor\mustache\PHPMailer\src\PHPMailer.php");
include ("vendor\mustache\PHPMailer\src\Exception.php");

use PHPMailer\src\Exception;
class UserModel
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function registrarJugador($nombre, $apellido, $ano_de_nacimiento, $sexo, $mail, $contrasena, $nombre_de_usuario, $foto_de_perfil, $hash_activacion)
    {
        // Inserta en jugador
        $sql = "INSERT INTO usuario (nombre_de_usuario, contrasena, nombre, apellido, ano_de_nacimiento, sexo, mail, foto_de_perfil, pais, ciudad, cuenta_verificada, hash_activacion)
                   VALUES ('$nombre_de_usuario', '$contrasena', '$nombre', '$apellido', '$ano_de_nacimiento', '$sexo', '$mail', '$foto_de_perfil', '...', '..', FALSE, '$hash_activacion')";
        
        $this->database->execute($sql);

        // Obtén el ID del usuario recién insertado e insertarlo en jugador
        $idJugador = $this->database->getLastInsertId();
        $sqlJugador = "INSERT INTO jugador (id) VALUES ($idJugador)";

        $this->database->execute($sqlJugador);
    }

    public function LogInconsulta($usuario, $password)
    {

        $sql = "SELECT * FROM usuario WHERE nombre_de_usuario = '$usuario' AND contrasena= '$password'";

        $result = $this->database->execute($sql);

        if ($result->num_rows == 1 && $this->emailVerificado()) {
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
    $mail = new PHPMailer(true);

    try {
        // Configurar servidor SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.office365.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'correoverificador2023@hotmail.com';
        $mail->Password = 'admin2023';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Configurar remitente y destinatario
        $mail->setFrom('correoverificador2023@hotmail.com', 'Admin');
        $mail->addAddress($email, $nombre);

        // Configurar contenido del correo
        $mail->isHTML(true);
        $mail->Subject = 'Activación de cuenta';
        $mail->Body    = "Hola $nombre,<br><br>Por favor haz clic en el siguiente enlace para activar tu cuenta:<br>";
        $mail->Body    .= "<a href='http://localhost/index.php?controller=Activacion&action=activar&codigo=$hash_activacion'>Activar cuenta</a>";

        $mail->send();
        echo 'El correo electrónico de activación se ha enviado correctamente.';
    } catch (Exception $e) {
        echo "Error al enviar el correo electrónico: {$mail->ErrorInfo}";
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