<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/clientes.php');

// Recibir parámetro de control
$control = $_GET['control'];

$cli = new Clientes($conexion);

switch ($control) {
    case 'consulta':
        $vec = $cli->consulta();
        break;
    
    case 'insertar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba2"}';
      $params = json_decode($json);
      $vec = $cli->insertar($params);
      break;

    case 'eliminar':
      $id = $_GET['id'];
      $vec = $cli->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $cli->editar($id, $params);
      break;

    // case 'filtro':
    //   //$datosj = $_GET ['dato'];
    //   $vec = $cli->filtro($dato);
    //   break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $cli->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>