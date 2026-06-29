<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

$control = $_GET['control'] ?? '';
$com = new Compras($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta': echo json_encode($com->consulta()); break;
    case 'insertar': echo json_encode($com->insertar($params)); break;
    case 'editar': echo json_encode($com->editar($_GET['id'], $params)); break;
    case 'eliminar': echo json_encode($com->eliminar($_GET['id'])); break;
    default: echo json_encode(["Resultado" => "ERROR"]); break;
}
exit();
?>