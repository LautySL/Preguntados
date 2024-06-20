<?php

require_once __DIR__ . '/../helper/Database.php';
require_once __DIR__ . '/../Model/SuggestedQuestionModel.php';

class SuggestQuestionController {
    private $model;
    private $presenter;

    public function __construct($model, $presenter) {
        $this->model = $model;
        $this->presenter = $presenter;
    }

    public function get() {
        $this->renderSuggestQuestionForm();
    }

    public function post() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pregunta = $_POST['question'];
            $categoria = $_POST['sugerirCategoria'];
            $idUsuario = $_SESSION['id_usuario'];

            $respuestas = [
                ['respuesta' => $_POST['correctAnswer'], 'es_correcta' => 1],
                ['respuesta' => $_POST['wrongAnswer1'], 'es_correcta' => 0],
                ['respuesta' => $_POST['wrongAnswer2'], 'es_correcta' => 0],
                ['respuesta' => $_POST['wrongAnswer3'], 'es_correcta' => 0]
            ];

            if ($this->model->addSuggestedQuestion($pregunta, $categoria, $idUsuario, $respuestas)) {
                echo "Pregunta sugerida con éxito. Será añadida al juego en cuanto la apruebe un editor.";
            } else {
                echo "Error al sugerir la pregunta.";
            }
        } else {
            $this->renderSuggestQuestionForm();
        }
    }

    private function renderSuggestQuestionForm() {
        // Renderiza la vista del formulario de sugerir pregunta
        echo $this->presenter->render('suggestQuestion', []);
    }
}