<?php
// Cabeceras CORS y de tipo JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

// Si es una petición OPTIONS, terminar aquí (soluciona problemas de preflight en Angular)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

require_once('../modelos/conexion.php');
require_once('../modelos/categoria.php');

$control = $_GET['control'] ?? '';
$cate = new Categoria($conexion);
$vec = [];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $cate->consulta();
        break;
    case 'insertar':
        $vec = ($params) ? $cate->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        break;
    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = ($params) ? $cate->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
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
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no definida"];
        break;
}

// Salida única y limpia
echo json_encode($vec);
?>