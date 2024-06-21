<?php

class AdminModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function totalJugadores()
    {
        $query = "SELECT COUNT(*) AS total_jugadores FROM jugador";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['total_jugadores'];

    }

    public function totalPartidas()
    {
        $query = "SELECT COUNT(*) AS total_partidas FROM partida";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['total_partidas'];

    }

    public function totalPreguntas()
    {
        $query = "SELECT COUNT(*) AS total_preguntas FROM pregunta";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['total_preguntas'];
    }

    //TODO por que hay que agregar al editor y que este pueda dar de alta las preguntas
    public function totalPreguntasCreadas()
    {
        $query = "SELECT COUNT(*) AS total_preguntas_creadas FROM pregunta WHERE DATE(fecha_creacion) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        $result = $this->database->execute($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['total_preguntas_creadas'];
        } else {
            // Manejar el caso de error o resultado vacío
            return 0; // O un manejo de error adecuado según tu aplicación
        }
    }

    //TODO por que hay que modificar la bd para que ande agregandole una fecha de creacion a cada user
    public function usuariosNuevos()
    {
        $query = "SELECT * AS usuarios_nuevos FROM usuario WHERE id > 11";
        $result = $this->database->execute($query);
        $row = $result->fetch_assoc();
        return $row['usuarios_nuevos'];
    }

    public function totalCorrectas()
    {
        $query = "SELECT p.categoría,
                    COUNT(*) AS total_preguntas,
                    SUM(pp.se_respondio_bien) AS total_correctas,
                    ROUND((SUM(pp.se_respondio_bien) / COUNT(*)) * 100, 2) AS porcentaje_correctas
                    FROM pregunta p
                    LEFT JOIN partida_pregunta pp ON p.id = pp.pregunta
                    GROUP BY p.categoría;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorPais()
    {
        $query = "SELECT pais, COUNT(*) AS total_usuarios_por_pais
                    FROM usuario 
                    GROUP BY pais;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorSexo()
    {
        $query = "SELECT sexo, COUNT(*) AS total_usuarios_por_sexo
                    FROM usuario 
                    GROUP BY sexo;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function totalUsuariosPorRango()
    {
        $query = "SELECT CASE
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) < 18 THEN 'menor'
                    WHEN TIMESTAMPDIFF(YEAR, ano_de_nacimiento, CURDATE()) >= 65 THEN 'jubilado'
                        ELSE 'medio'
                    END AS rango_etario,
                        COUNT(*) AS total_usuarios_por_rango
                                FROM usuario
                                GROUP BY rango_etario;";
        $result = $this->database->execute($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}