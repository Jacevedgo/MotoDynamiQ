<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

require_once('../modelos/conexion.php');
require_once('../modelos/motocicletas.php');

$control = $_GET['control'] ?? '';
$mon = new Motocicletas($conexion);

$json = file_get_contents('php://input');
$params = json_decode($json);
$vec = []; 

switch ($control) {
    case 'consulta':
        $vec = $mon->consulta();
        break;
    case 'insertar':
        $vec = $params ? $mon->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        $vec = $mon->eliminar($id);
        break;
    case 'editar':
        $id = $_GET['id'] ?? 0;
        $vec = $params ? $mon->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'filtro':
        $dato = $_GET['dato'] ?? '';
        $vec = $mon->filtro($dato);
        break;
    case 'categorias':
        $vec = $mon->obtenerCategorias();
        break;
}

echo json_encode($vec);
?>