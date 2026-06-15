<?php
// 1. BLINDAJE: Evita que errores de PHP rompan el JSON
error_reporting(0);
@ini_set('display_errors', 0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/reportes.php');

$control = $_GET['control'] ?? '';
$rep = new Reportes($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);
$vec = [];

try {
    switch ($control) {
        case 'consulta': $vec = $rep->consulta(); break;
        case 'insertar': $vec = ($params) ? $rep->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; break;
        case 'editar': 
            $id = $_GET['id'] ?? 0;
            $vec = ($params) ? $rep->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; 
            break;
        case 'eliminar': $vec = $rep->eliminar($_GET['id'] ?? 0); break;
        case 'filtro': $vec = $rep->filtro($_GET['dato'] ?? ''); break;
        default: $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
    }
} catch (Exception $e) {
    // Si falla el modelo, capturamos el error y devolvemos JSON válido
    $vec = ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
}

// 2. LIMPIEZA: Eliminamos cualquier basura del búfer antes de imprimir
if (ob_get_length()) ob_clean();

echo json_encode($vec);
exit;
?>