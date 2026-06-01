<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

// Recibir parámetro de control
$control = $_GET['control'];

$com = new Compras($conexion);

switch ($control) {
    case 'consulta':
        $vec = $com->consulta();
        break;

    case 'insertar':
    $json = '{"fecha":"2026-05-10","proveedor_id":1,"usuario_id":1,"total":150000.50}';
    $params = json_decode($json);
    $vec = $com->insertar($params);
    break;


    case 'eliminar':
      $id = $_GET['id'];
      $vec = $com->eliminar($id);
      break;

    case 'editar':
      $json = '{"nombre":"Prueba4"}';
      $params = json_decode($json);
      $id = $_GET['id'];
      $vec = $com->editar($id, $params);
      break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $com->filtro($dato);
    break;


}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>