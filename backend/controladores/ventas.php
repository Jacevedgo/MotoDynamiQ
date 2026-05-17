<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php');

// Recibir parámetro de control
$control = $_GET['control'];

$vet = new Ventas($conexion);

switch ($control) {
    case 'consulta':
        $vec = $vet->consulta();
        break;

    case 'insertar':
    // El JSON debe tener los nombres exactos que usas en el modelo
    $json = '{
        "fecha": "2026-05-10",
        "cliente_id": 1,
        "usuario_id": 1,
        "total": 150.50
    }';
    $params = json_decode($json);
    $vec = $vet->insertar($params);
        break;



    case 'eliminar':
      $id = $_GET['id'];
      $vec = $vet->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $vet->editar($id, $params);
      break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $vet->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>