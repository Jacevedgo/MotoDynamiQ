<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/categoria.php');

$control = isset($_GET['control']) ? $_GET['control'] : '';
$cate = new Categoria($conexion);
$vec = [];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $cate->consulta();
        break;
    case 'insertar':
        $vec = ($params) ? $cate->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = ($params) ? $cate->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $cate->eliminar($id);
        break;
    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $cate->filtro($dato);
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Control no válido"];
        break;
}

echo json_encode($vec);
?>
    