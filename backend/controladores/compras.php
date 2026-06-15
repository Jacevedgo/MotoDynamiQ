<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

$control = $_GET['control'] ?? '';
$com = new Compras($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);

try {
    switch ($control) {
        case 'consulta': $vec = $com->consulta(); break;
        case 'insertar': $vec = $params ? $com->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"]; break;
        case 'editar': $vec = $params ? $com->editar($_GET['id'], $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"]; break;
        case 'eliminar': $vec = $com->eliminar($_GET['id']); break;
        default: $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no definida"];
    }
} catch (Exception $e) {
    $vec = ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
}
echo json_encode($vec);
exit;
?>