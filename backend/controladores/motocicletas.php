<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

require_once('../modelos/conexion.php');
require_once('../modelos/motocicletas.php');

$vec = []; 
$control = $_GET['control'] ?? '';

$moto = new Motocicletas($conexion);

switch ($control) {
    case 'consulta':
        $vec = $moto->consulta();
        break;
    case 'insertar':
        $json = file_get_contents('php://input'); 
        $params = json_decode($json);
        $vec = $moto->insertar($params);
        break;
    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        $vec = $moto->eliminar($id);
        break;
    case 'editar':
        $id = $_GET['id'] ?? 0;
        $json = file_get_contents('php://input'); 
        $params = json_decode($json);
        $vec = $moto->editar($id, $params);
        break;
    case 'filtro':
        $dato = $_GET['dato'] ?? '';
        $vec = $moto->filtro($dato);
        break;
    default:
        $vec = array("Resultado" => "ERROR", "Mensaje" => "Control no especificado o no valido");
        break;
}

echo json_encode($vec);
?>