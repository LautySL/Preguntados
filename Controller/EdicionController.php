<?php

class EdicionController
{


    private $model;
    private $Presenter;

    public function __construct($Model, $Presenter)
    {
        $this->model = $Model;
        $this->Presenter = $Presenter;
    }



    public function get()
    {
        $this->Presenter->render("view/vistaEditor.mustache");
    }

    public function verPreguntas()
    {
        $preguntas = $this->model->getPreguntas();

        $data = [];

        foreach ($preguntas as $pregunta) {
            $respuesta = $this->model->getRespuestaCorrectaByPreguntaId($pregunta['id']);

            $data['total_preguntas'][] = [
                'id' => $pregunta['id'],
                'fecha' => $pregunta['fecha_creacion_pregunta'],
                'pregunta' => $pregunta['pregunta'],
                'respuesta' => $respuesta
            ];
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $pregunta = $this->model->getPreguntaById($id);

            if ($pregunta) {
                $respuesta = $this->model->getRespuestaCorrectaByPreguntaId($id);

                $data['modificar_pregunta'] = [
                    'id' => $pregunta['id'],
                    'pregunta' => $pregunta['pregunta'],
                    'respuesta' => $respuesta
                ];
            } else {
                // Manejar el caso donde no se encuentra la pregunta
                $data['modificar_pregunta'] = [
                    'id' => $id,
                    'pregunta' => 'Pregunta no encontrada',
                    'respuesta' => 'Respuesta no encontrada'
                ];
            }
        }

        $this->Presenter->render('view/vistaEditor.mustache', $data);
    }

    public function verPreguntasReportadas()
    {
        $preguntasReportadas = $this->model->getPreguntasReportadas();
    
        $data = [];
    
        foreach ($preguntasReportadas as $reporte) {

            $respuesta = $this->model->getRespuestaCorrectaByPreguntaId($reporte['id']);

            $data['preguntas_reportadas'][] = [
                'id' => $reporte['id'],
                'fecha' => $reporte['fecha_reporte'],
                'pregunta' => $reporte['pregunta'],
                'respuesta' => $respuesta
            ];
        }
    
        $this->Presenter->render('view/vistaEditor.mustache', $data);
    }

    public function verPreguntasSugerida()
    {
        $preguntas = $this->model->getPreguntasSugeridas();
    
        $data = [];
    
        foreach ($preguntas as $pregunta) {
            
            $respuesta = $this->model->getRespuestaCorrectaByPreguntaId($pregunta['id']);
    
            $data['total_preguntas'][] = [
                'id' => $pregunta['id'],
                'fecha' => $pregunta['fecha_creacion_pregunta'],
                'pregunta' => $pregunta['pregunta'],
                'respuesta' => $respuesta
            ];
        }
    
        // Renderizar la vista Mustache con los datos estructurados
        $this->Presenter->render('view/vistaEditor.mustache', $data);
    }

    public function eliminarPregunta()
    {
        if (isset($_GET['id']) && isset($_GET['tipo'])) {
            $id = $_GET['id'];
            $tipo = $_GET['tipo'];
    
            switch ($tipo) {
                case 'normal':
                    $eliminar = $this->model->eliminarPregunta($id);
                    if ($eliminar) {
                        header('Location: /edicion/verPreguntas');
                        exit;
                    } else {
                        echo "Error al intentar eliminar la pregunta.";
                    }
                    break;
                case 'reportada':
                    $eliminar = $this->model->eliminarPreguntaReportada($id);
                    if ($eliminar) {
                        header('Location: /edicion/verPreguntasReportadas');
                        exit;
                    } else {
                        echo "Error al intentar eliminar la pregunta reportada.";
                    }
                    break;
                case 'sugeridas':
                    $eliminar = $this->model->eliminarPreguntaSugeridas($id);
                    if ($eliminar) {
                        header('Location: /edicion/verPreguntasSugeridas');
                        exit;
                    } else {
                        echo "Error al intentar eliminar la pregunta sugerida.";
                    }
                    break;
                default:
                    echo "Tipo de pregunta no válido.";
                    return;
            }
    
        } else {
            echo "No se ha proporcionado el ID de la pregunta o el tipo.";
        }
    }
    
    public function modificarPregunta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nuevaPregunta = $_POST['modificarPregunta'];
            $nuevaRespuesta = $_POST['modificarRespuesta'];
    
            $modificado = $this->model->modificarPregunta($id, $nuevaPregunta, $nuevaRespuesta);
    
            if ($modificado) {
                header('Location: /edicion/verPreguntas');
                exit;
            } else {
                echo "Error al intentar modificar la pregunta.";
            }
        } else {
            echo "Método no permitido para modificar la pregunta.";
        }
    }
    public function sugerirPregunta()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $pregunta = $_POST['sugerirPregunta'];
            $respuesta = $_POST['sugerirRespuesta'];
            $categoria = $_POST['sugerirCategoria'];
    
            $sugerido = $this->model->sugerirPregunta($id, $pregunta, $respuesta, $categoria);
    
            if ($sugerido) {
                echo "Gracias por tua aporte, consideraremos tu sugerencia.";
                header('Location: /home/get');
                exit;
            } else {
                echo "Error al intentar sugerir la pregunta.";
            }
        } else {
            echo "Método no permitido para sugerir la pregunta.";
        }
    }
    

}