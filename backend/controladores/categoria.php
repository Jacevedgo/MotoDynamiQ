<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/categoria.php');

$control = $_GET['control'] ?? '';
$cate = new Categoria($conexion);

switch ($control) {
    case 'consulta':
        $vec = $cate->consulta();
        break;
    
    case 'insertar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba2"}';
      $params = json_decode($json);
      $vec = $cate->insertar($params);
      break;

    case 'eliminar':
      $id = $_GET['id'];
      $vec = $cate->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $cate->editar($id, $params);
      break;

    case 'filtro':
      //$datosj = $_GET ['dato'];
      $vec = $cate->filtro($dato);
      break;
}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>



    