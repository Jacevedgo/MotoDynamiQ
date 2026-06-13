<?php
// 1. Configuración de errores para evitar que advertencias rompan el JSON
error_reporting(0);
ini_set('display_errors', 0);

// 2. Encabezados definidos ANTES de cualquier salida
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

// Manejo de petición OPTIONS (pre-flight de Angular)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit;
}

$control = $_GET['control'] ?? '';
$usu = new Usuarios($conexion);
$vec = [];

// Captura de datos JSON para peticiones POST
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $usu->consulta();
        break;
    case 'insertar':
        $vec = $usu->insertar($params);
        break;
    case 'eliminar':
        $id = $_GET['id'] ?? 0;
        $vec = $usu->eliminar($id);
        break;
    case 'editar':
        $id = $_GET['id'] ?? 0;
        $vec = $usu->editar($id, $params);
        break;
    case 'filtro':
        $dato = $_GET['dato'] ?? '';
        $vec = $usu->filtro($dato);
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Control no definido"];
}

// 3. Imprimir el JSON una sola vez
echo json_encode($vec);
?>