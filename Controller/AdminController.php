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
        $this->presenter->render("view/home/get");
    }

    private function request($metodo)
    {
       $this ->limpiarCarpeta();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dateFrom = $_POST['dateFrom'] ?? NULL;
            $dateTo = $_POST['dateTo'] ?? NULL;

            if (!empty($dateFrom) && !empty($dateTo)) {
                try {
                    $data = call_user_func([$this->model, $metodo], $dateFrom, $dateTo);
                    $filenameBarra = isset($data['filenameBarra']) ? $data['filenameBarra'] : '';
                    $filenameLinea = isset($data['filenameLinea']) ? $data['filenameLinea'] : '';

                    $viewData = [
                        $metodo . '_barra' => $filenameBarra,
                        $metodo . '_linea' => $filenameLinea,
                    ];

                    $this->presenter->render('view/presentarDatos.mustache', $viewData);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                $this->presenter->render("view/presentarDatos.mustache");
            }
        } elseif (isset($_POST['imprimir'])) {
            if (!empty($dateFrom) && !empty($dateTo)) {
                try {
                    $data = call_user_func([$this->model, $metodo], $dateFrom, $dateTo);
                    $filenameBarra = isset($data['filenameBarra']) ? $data['filenameBarra'] : '';
                    $filenameLinea = isset($data['filenameLinea']) ? $data['filenameLinea'] : '';

                    $html = "
                        <html>
                        <head><title>Estadísticas del juego</title></head>
                        <body>
                            <h1>Estadísticas del juego</h1>
                            <p>Total de jugadores en barra: $filenameBarra</p>
                            <p>Total de jugadores en línea: $filenameLinea</p>
                        </body>
                        </html>
                    ";

                    $pdfCreator = new PdfCreator();
                    $pdfCreator->create($html);
                    exit;

                    //$this->presenter->render('view/presentarDatos.mustache', $viewData);
                } catch (Exception $e) {
                    echo "Error: " . $e->getMessage();
                }
            } else {
                $this->presenter->render("view/presentarDatos.mustache");
            }

        } else {
            $this->presenter->render("view/presentarDatos.mustache");
        }
    }

    public function totalJugadores()
    {
        $this->request('totalJugadores');
    }

    public function totalPartidas()
    {
        $this->request('totalPartidas');
    }

    public function totalPreguntas()
    {
        $this->request('totalPreguntas');
    }
    public function totalPreguntasCreadas()
    {
        $this->request('totalPreguntasCreadas');
    }

    public function usuariosNuevos()
    {
        $this->request('usuariosNuevos');
    }

    public function totalCorrectas()
    {
        $this->request('totalCorrectas');
    }

    public function totalUsuariosPorPais()
    {
        $this->request('totalUsuariosPorPais');
    }

    public function totalUsuariosPorSexo()
    {
        $this->request('totalUsuariosPorSexo');
    }

    public function totalUsuariosPorRango()
    {
        $this->request('totalUsuariosPorRango');
    }

    public function descargarPDF()
    {
        $graficos_barra = $_POST['grafico_barra'];
        $graficos_linea = $_POST['grafico_linea'];

        $graficos_barra_array = array_filter(explode(',', $graficos_barra));
        $graficos_linea_array = array_filter(explode(',', $graficos_linea));

        $html = '<h1>Estadísticas Generadas</h1>';

        foreach ($graficos_barra_array as $grafico_barra) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/public/img/grafico/' . $grafico_barra;
            if (file_exists($path)) {
                $base64 = base64_encode(file_get_contents($path));
                $html .= '<img src="data:image/png;base64,' . $base64 . '" alt="Gráfico de Barras">';
            }
        }

        foreach ($graficos_linea_array as $grafico_linea) {
            $path = $_SERVER['DOCUMENT_ROOT'] . '/public/img/grafico/' . $grafico_linea;
            if (file_exists($path)) {
                $base64 = base64_encode(file_get_contents($path));
                $html .= '<img src="data:image/png;base64,' . $base64 . '" alt="Gráfico de Líneas">';
            }
        }

        $this->model->descargarPDF($html);
    }

    private function checkLoggedIn()
    {
        if (!isset($_SESSION['usuario'])) {
            header('Location: /');
            exit();
        }
    }
    public function limpiarCarpeta() {
        // Función para limpiar la carpeta public/img/grafico/
        $carpeta = 'public/img/grafico/';

        // Asegurarse de que el camino tenga una barra diagonal al final
        $carpeta = rtrim($carpeta, '/') . '/';

        if (is_dir($carpeta) && strpos(realpath($carpeta), realpath('public/img/grafico')) === 0) {
            $archivos = glob($carpeta . '*');

            foreach ($archivos as $archivo) {
                if (is_dir($archivo)) {
                    $this->limpiarCarpeta($archivo);
                    rmdir($archivo);
                } else {
                    unlink($archivo);
                }
            }
            echo "Contenido de la carpeta '$carpeta' ha sido eliminado.";
            header('Location: /');
        }
    }

}
