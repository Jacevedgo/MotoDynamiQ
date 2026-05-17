<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/reportes.php');

// Recibir parámetro de control
$control = $_GET['control'];

$rep = new Reportes($conexion);

switch ($control) {
    case 'consulta':
        $vec = $rep->consulta();
        break;
        
    case 'insertar':
    $json = '{"titulo":"Reporte de Ventas","descripcion":"Ventas del mes de abril","fecha":"2026-05-10"}';
    $params = json_decode($json);
    $vec = $rep->insertar($params);
    break;




    case 'eliminar':
      $id = $_GET['id'];
      $vec = $rep->eliminar($id);
      break;

    case 'editar':
      //$json = file_get_contents('php://input');
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $rep->editar($id, $params);
      break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $rep->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>