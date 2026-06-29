<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

// BLINDAJE: Evita errores de PHP que rompen el formato JSON
error_reporting(0);
ini_set('display_errors', 0);

require_once('../modelos/conexion.php');
require_once('../modelos/reportes.php');

$control = $_GET['control'] ?? '';
$rep = new Reportes($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta': echo json_encode($rep->consulta()); break;
    case 'insertar': echo json_encode($params ? $rep->insertar($params) : ["Resultado"=>"ERROR"]); break;
    case 'eliminar': echo json_encode($rep->eliminar($_GET['id'] ?? 0)); break;
    case 'editar':   echo json_encode($params ? $rep->editar($_GET['id'] ?? 0, $params) : ["Resultado"=>"ERROR"]); break;
    case 'filtro':   echo json_encode($rep->filtro($_GET['dato'] ?? '')); break;
    default:         echo json_encode(["Resultado"=>"ERROR", "Mensaje"=>"Acción no válida"]); break;
}
exit();
?>