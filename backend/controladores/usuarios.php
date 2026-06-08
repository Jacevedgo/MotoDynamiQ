<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json');

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

// Recibir parámetro de control
$control = $_GET['control'];

$usu = new Usuarios($conexion);

switch ($control) {
    case 'consulta':
        $vec = $usu->consulta();
        break;
    
    case 'insertar':
    $json = file_get_contents('php://input'); // Leemos el JSON real que envía Angular desde el formulario
    $params = json_decode($json);
    $vec = $usu->insertar($params);
    break;

    case 'eliminar':
      $id = $_GET['id'];
      $vec = $usu->eliminar($id);
      break;

    case 'editar':
    $id = $_GET['id'] ?? 0;
    // Capturamos lo que Angular realmente envía
    $json = file_get_contents('php://input'); 
    $params = json_decode($json);
    $vec = $usu->editar($id, $params);
    break;

    case 'filtro':
    $dato = $_GET['dato'] ?? '';
    $vec = $usu->filtro($dato);
    break;

}
      $datosj = json_encode($vec);
      echo $datosj;
      header('Content-Type: application/json');

?>