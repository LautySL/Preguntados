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
    
        // Renderizar la vista Mustache con los datos estructurados
        $this->Presenter->render('view/vistaEditor.mustache', $data);
    }

    public function eliminarPregunta()
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $eliminar = $this->model->eliminarPregunta($id);
    
            if ($eliminar) {
                header('Location: /edicion/verPreguntas');
                exit;
            } else {
                echo "Error al intentar eliminar la pregunta.";
            }
        } else {
            echo "No se ha proporcionado el ID de la pregunta a eliminar.";
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
    

}