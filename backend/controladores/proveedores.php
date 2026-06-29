<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

// BLINDAJE: Ocultar errores para que no rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

require_once('../modelos/conexion.php');
require_once('../modelos/proveedores.php');

$control = $_GET['control'] ?? '';
$pro = new Proveedores($conexion);
$vec = [];

// Solo leemos el JSON si realmente es una operación que lo requiere
$params = null;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json = file_get_contents('php://input');
    $params = json_decode($json);
}

switch ($control) {
    case 'consulta':
        $vec = $pro->consulta();
        break;
    case 'insertar':
        $vec = $params ? $pro->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $pro->eliminar($id);
        break;
    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $params ? $pro->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"];
        break;
    case 'filtro':
        $dato = $_GET['dato'] ?? '';
        $vec = $pro->filtro($dato);
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción inválida"];
        break;
}

echo json_encode($vec);
exit();
?>