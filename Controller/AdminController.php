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



    public function totalJugadores(){
        $totalJugadores = $this->model->totalJugadores();
        $data = [
            'total_jugadores' => $totalJugadores
        ];
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    public function totalPartidas(){
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
    // Ejemplo de método para generar gráfico de barras con JPGraph
    public function generarGraficoJPGraph($datos)
    {
        require_once ('vendor/jpgraph/jpgraph.php');
        require_once ('vendor/jpgraph/jpgraph_bar.php');
        require_once('vendor/jpgraph/jpgraph_line.php');

        $data = array($datos['categoria1'], $datos['categoria2'], $datos['categoria3']);
        $graph = new Graph(400, 300, 'auto');
        $graph->SetScale('textlin');

        // Crear el gráfico de barras
        $barplot = new BarPlot($data);
        $barplot->SetFillColor('blue');
        $barplot->SetWidth(0.5);
        $graph->Add($barplot);

        // Configurar título y ejes
        $graph->title->Set('Gráfico de Ejemplo');
        $graph->xaxis->title->Set('Categorías');
        $graph->yaxis->title->Set('Valores');

        // Capturar la salida del gráfico como una imagen
        ob_start();
        $graph->Stroke();
        $img_data = ob_get_clean();

        // Renderizar la vista Mustache con los datos del gráfico
        $data['grafico'] = base64_encode($img_data); // Convertir imagen a base64 para mostrar en la vista
        $this->presenter->render('view/presentarDatos.mustache', $data);
    }

    //     // Datos para el gráfico
    //     $valores = [];
    //     $categorias = [];

    //     foreach ($datos as $dato) {
    //         $valores[] = $dato['valor'];
    //         $categorias[] = $dato['nombre'];
    //     }

    //     // Crear el gráfico
    //     $graph = new Graph(800, 600);
    //     $graph->SetScale('textlin');
    //     $graph->title->Set('Gráfico de Ejemplo');
    //     $graph->SetBox(false);

    //     // Crear la barra de datos
    //     $barplot = new BarPlot($valores);
    //     $barplot->SetFillColor('lightblue');
    //     $barplot->value->Show();

    //     // Añadir la barra al gráfico
    //     $graph->Add($barplot);
    //     $graph->Stroke();
    // }
    
}
