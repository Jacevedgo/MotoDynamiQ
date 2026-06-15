<?php
// Configuración de errores para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php');

$control = $_GET['control'] ?? '';
$ven = new Ventas($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);
$vec = [];

try {
    switch ($control) {
        case 'consulta': $vec = $ven->consulta(); break;
        case 'insertar': $vec = ($params) ? $ven->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"]; break;
        case 'editar': 
            $id = $_GET['id'] ?? 0;
            $vec = ($params) ? $ven->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"]; 
            break;
        case 'eliminar': $vec = $ven->eliminar($_GET['id'] ?? 0); break;
        default: $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
    }
} catch (Exception $e) {
    $vec = ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
}

if (ob_get_length()) ob_clean();
echo json_encode($vec);
exit;
?>