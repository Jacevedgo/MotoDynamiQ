<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/motocicletas.php');

$control = isset($_GET['control']) ? $_GET['control'] : '';
$mon = new Motocicletas($conexion);
$vec = [];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $mon->consulta();
        break;
    case 'insertar':
        $vec = ($params) ? $mon->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = ($params) ? $mon->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $mon->eliminar($id);
        break;
    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $mon->filtro($dato);
        break;
    case 'categorias':
        $vec = $mon->obtenerCategorias();
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
        break;
}

echo json_encode($vec);
?>