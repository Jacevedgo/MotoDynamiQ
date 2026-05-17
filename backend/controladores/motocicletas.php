<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/motocicletas.php');

// Recibir parámetro de control
$control = $_GET['control'];

$mon = new Motocicletas($conexion);

switch ($control) {
    case 'consulta':
        $vec = $mon->consulta();
        break;
    
    case 'insertar':
    $json = '{"marca":"Honda","modelo":"CBR500R","cilindraje":500,"precio":32000.00,"fo_categoria":1}';
    $params = json_decode($json);
    $vec = $mon->insertar($params);
    break;



    case 'eliminar':
      $id = $_GET['id'];
      $vec = $mon->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $mon->editar($id, $params);
      break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $mon->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>
