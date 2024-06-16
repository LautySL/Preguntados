<?php

class AdminModel
{

    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function totalJugadores(){
        $query = "SELECT COUNT(*) AS total_jugadores FROM jugador";
        return $this->database->execute($query);
    }

    public function totalPartidas(){
        $query = "SELECT COUNT(*) AS total_partidas FROM partida";
        return $this->database->execute($query);
    }

    public function totalPreguntas(){
        $query = "SELECT COUNT(*) AS total_preguntas FROM pregunta";
        return $this->database->execute($query);
    }

    public function totalPreguntasCreadas(){
        $query = "SELECT COUNT(*) AS total_preguntas_creadas FROM pregunta WHERE DATE(fecha_creacion) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
        return $this->database->execute($query);
    }

    public function usuariosNuevos(){
        $query = "SELECT * AS usuarios_nuevos FROM usuarios WHERE id > 11";
        return $this->database->execute($query);
    }

    public function totalCorrectas(){
        $query = "SELECT
                    p.categoría,
                    COUNT(*) AS total_preguntas,
                    SUM(pp.se_respondio_bien) AS total_correctas,
                    ROUND((SUM(pp.se_respondio_bien) / COUNT(*)) * 100, 2) AS porcentaje_correctas
                    FROM pregunta p
                    LEFT JOIN partida_pregunta pp ON p.id = pp.pregunta
                    GROUP BY p.categoría;";
        return $this->database->execute($query);
    }

    public function totalUsuariosPorPais(){
        $query = "SELECT pais, COUNT(*) AS total_usuarios_por_pais
                    FROM usuarios 
                    GROUP BY pais;";
        return $this->database->execute($query);
    }

    public function totalUsuariosPorSexo(){
        $query = "SELECT sexo, COUNT(*) AS total_usuarios_por_sexo
                    FROM usuarios 
                    GROUP BY sexo;";
        return $this->database->execute($query);
    }

    public function totalUsuariosPorRango(){
        $query = "SELECT
                    CASE
                    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) < 18 THEN 'menor'
                    WHEN TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= 65 THEN 'jubilado'
                        ELSE 'medio'
                    END AS rango_etario,
                        COUNT(*) AS total_usuarios_por_rango
                                FROM usuarios
                                GROUP BY rango_etario;";
        return $this->database->execute($query);
    }
    

}