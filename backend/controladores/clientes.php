<?php
error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/clientes.php');

$control = $_GET['control'] ?? '';
$cli = new Clientes($conexion);
$vec = ["Resultado" => "ERROR", "Mensaje" => "Control no válido"];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta': $vec = $cli->consulta(); break;
    case 'insertar': $vec = $params ? $cli->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; break;
    case 'editar': 
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $params ? $cli->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; 
        break;
    case 'eliminar': 
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $cli->eliminar($id); 
        break;
    case 'filtro': 
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $cli->filtro($dato); 
        break;
}

echo json_encode($vec);
exit; // 🔥 CRUCIAL: Detiene cualquier salida posterior
?>