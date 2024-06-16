// generateImage.php
<?php
require_once ('jpgraph/jpgraph.php');
require_once ('jpgraph/jpgraph_bar.php');

// Obten los datos necesarios del controlador
// Aquí necesitarías ajustar el código para recibir los datos correctos del usuario
$usuario_id = $_GET['usuario_id'];
$datos_graficos = array(); // Obtén los datos de los gráficos del controlador

// Aquí iría el código para generar los gráficos utilizando JPGraph
// Por simplicidad, omitiré el código específico de JPGraph
// Simplemente supongamos que $grafico_jugadores y $grafico_partidas son instancias válidas de gráficos de JPGraph
// y que ya tienen sus datos configurados y se han creado adecuadamente

// Guardar los gráficos como imágenes
$grafico_jugadores->Stroke('images/jugadores.png');
$grafico_partidas->Stroke('images/partidas.png');

// Redirigir a la vista del dashboard
header('Location: dashboard.php');
?>
