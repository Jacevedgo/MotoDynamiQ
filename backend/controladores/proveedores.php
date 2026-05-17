<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/proveedores.php');

// Recibir parámetro de control
$control = $_GET['control'];

$pro = new Proveedores($conexion);

switch ($control) {
    case 'consulta':
        $vec = $pro->consulta();
        break;
    
    case 'insertar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba2"}';
      $params = json_decode($json);
      $vec = $pro->insertar($params);
      break;

    case 'eliminar':
      $id = $_GET['id'];
      $vec = $pro->eliminar($id);
      break;

    case 'editar':
    $id = $_GET['id'];   // si no viene, usa 0
    $json = '{"nombre":"Proveedor Actualizado","telefono":"123456789","direccion":"Calle 123"}';
    $params = json_decode($json);
    $vec = $pro->editar($id, $params);
    break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';   // inicializa la variable
    $vec = $pro->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>
