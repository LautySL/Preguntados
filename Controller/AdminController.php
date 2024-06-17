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
        $grafico = base64_encode($img_data);

    // Preparar datos para la plantilla Mustache
    return [
        'grafico' => $grafico,
        'titulo' => 'Ejemplo de Gráfico'
    ];
    }

    public function tipoDeVisualizacion()
    {
        // Obtener los datos según el filtro seleccionado
        $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'dia'; // Valor por defecto
    
        $datos = [
            'filtro' => $filtro,
            'total_jugadores' => $this->model->totalJugadores(),
            'total_partidas' => $this->model->totalPartidas(),
            'total_preguntas' => $this->model->totalPreguntas(),
            'total_preguntas_creadas' => $this->model->totalPreguntasCreadas(),
            'usuarios_nuevos' => $this->model->usuariosNuevos(),
            'total_correctas' => $this->model->totalCorrectas(),
            'total_usuarios_por_pais' => $this->model->totalUsuariosPorPais(),
            'total_usuarios_por_sexo' => $this->model->totalUsuariosPorSexo(),
            'total_usuarios_por_rango' => $this->model->totalUsuariosPorRango()
        ];
    
        // Generar gráfico usando JPGraph
        $graficoData = $this->generarGraficoJPGraph($datos);
        $datos['grafico'] = $graficoData['grafico'];
        $datos['titulo'] = 'Ejemplo de Gráfico'; // Puedes personalizar el título según sea necesario
    
        // Preparar datos para la lista
        $datos['lista'] = [
            ['nombre' => 'Ejemplo1', 'valor' => 'Valor1'],
            ['nombre' => 'Ejemplo2', 'valor' => 'Valor2'],
            // Agregar más datos según sea necesario
        ];
    
        // Renderizar la vista Mustache con los datos
        $this->presenter->render('view/presentarDatos.mustache', $datos);
    }

    
}
