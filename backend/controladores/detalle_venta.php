<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/detalle_venta.php');

// Recibir parámetro de control
$control = $_GET['control'];

$ven = new DetalleVentas($conexion);

switch ($control) {
    case 'consulta':
        $vec = $ven->consulta();
        break;
    
    case 'insertar':
    $json = '{"compra_id":1,"motocicleta_id":2,"cantidad":3,"subtotal":450000.00}';
    $params = json_decode($json);
    $vec = $ven->insertar($params);
    break;


    case 'eliminar':
      $id = $_GET['id'];
      $vec = $ven->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $ven->editar($id, $params);
      break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $ven->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>
