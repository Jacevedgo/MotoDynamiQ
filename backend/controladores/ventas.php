<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

error_reporting(0); // Blindaje contra errores de PHP
ini_set('display_errors', 0);

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php');

$control = $_GET['control'] ?? '';
$vet = new Ventas($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta': echo json_encode($vet->consulta()); break;
    case 'insertar': echo json_encode($params ? $vet->insertar($params) : ["Resultado" => "ERROR"]); break;
    case 'eliminar': echo json_encode($vet->eliminar($_GET['id'] ?? 0)); break;
    case 'editar': echo json_encode($params ? $vet->editar($_GET['id'] ?? 0, $params) : ["Resultado" => "ERROR"]); break;
    case 'filtro': echo json_encode($vet->filtro($_GET['dato'] ?? '')); break;
    default: echo json_encode(["Resultado" => "ERROR"]); break;
}
exit();
?>