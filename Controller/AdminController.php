<?php
class AdminController
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
        $this->checkLoggedIn();
        $this->presenter->render("view/admin.mustache");
    }

    public function totalJugadores() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dateFrom = $_POST['dateFrom'] ?? '';
            $dateTo = $_POST['dateTo'] ?? '';
        
            try {
                $data = $this->model->totalJugadores($dateFrom, $dateTo);
                
                // Renderizar la vista con Mustache
                $this->presenter->render('view/presentarDatos.mustache', ['data' => $data]);
            } catch (Exception $e) {
                // Manejar el error adecuadamente
                echo "Error: " . $e->getMessage();
            }
        } else {
            // Mostrar la vista inicial
            $this->presenter->render('view/presentarDatos.mustache');
        }

    }

    public function totalPartidas()
    {
        $totalPartidas = $this->model->totalPartidas();
        $data = [
            'total_partidas' => $totalPartidas
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalPreguntas()
    {
        $totalPreguntas = $this->model->totalPreguntas();
        $data = [
            'total_preguntas' => $totalPreguntas
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }
    public function totalPreguntasCreadas()
    {
        $totalPreguntasCreadas = $this->model->totalPreguntasCreadas();
        $data = [
            'total_preguntas_creadas' => $totalPreguntasCreadas
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function usuariosNuevos()
    {
        $usuariosNuevos = $this->model->usuariosNuevos();
        $data = [
            'usuarios_nuevos' => $usuariosNuevos
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalCorrectas()
    {
        $totalCorrectas = $this->model->totalCorrectas();
        $data = [
            'total_correctas' => $totalCorrectas
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalUsuariosPorPais()
    {
        $totalUsuariosPorPais = $this->model->totalUsuariosPorPais();
        $data = [
            'total_usuarios_por_pais' => $totalUsuariosPorPais
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalUsuariosPorSexo()
    {
        $totalUsuariosPorSexo = $this->model->totalUsuariosPorSexo();
        $data = [
            'total_usuarios_por_sexo' => $totalUsuariosPorSexo
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalUsuariosPorRango()
    {
        $totalUsuariosPorRango = $this->model->totalUsuariosPorRango();
        $data = [
            'total_usuarios_por_rango' => $totalUsuariosPorRango
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: /');
            exit();
        }
    }

    public function tipoDeVisualizacion()
    {

        // $datos = [
        //     'total_jugadores' => $this->model->totalJugadores(),
        //     'total_partidas' => $this->model->totalPartidas(),
        //     'total_preguntas' => $this->model->totalPreguntas(),
        //     'total_preguntas_creadas' => $this->model->totalPreguntasCreadas(),
        //     'usuarios_nuevos' => $this->model->usuariosNuevos(),
        //     'total_correctas' => $this->model->totalCorrectas(),
        //     'total_usuarios_por_pais' => $this->model->totalUsuariosPorPais(),
        //     'total_usuarios_por_sexo' => $this->model->totalUsuariosPorSexo(),
        //     'total_usuarios_por_rango' => $this->model->totalUsuariosPorRango()
        // ];

        // // Generar gráfico usando JPGraph
        // $graficoData = $this->($datos);
        // $datos['grafico'] = $graficoData['grafico'];
        // $datos['titulo'] = 'Ejemplo de Gráfico'; // Puedes personalizar el título según sea necesario

        // // Preparar datos para la lista
        // $datos['lista'] = [
        //     ['nombre' => 'Ejemplo1', 'valor' => 'Valor1'],
        //     ['nombre' => 'Ejemplo2', 'valor' => 'Valor2'],
        //     // Agregar más datos según sea necesario
        // ];

        // // Renderizar la vista Mustache con los datos
        // $this->presenter->render('view/presentarDatos.mustache', $datos);
    }


}
