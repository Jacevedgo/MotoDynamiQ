<?php
// 1. BLINDAJE: Suprimir errores de PHP para que no contaminen el JSON
error_reporting(0);
@ini_set('display_errors', 0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

// Manejar peticiones de pre-vuelo (CORS)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

$control = $_GET['control'] ?? '';
$usu = new Usuarios($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);
$vec = [];

try {
    switch ($control) {
        case 'consulta': 
            $vec = $usu->consulta(); 
            break;
        case 'insertar': 
            $vec = ($params) ? $usu->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; 
            break;
        case 'editar': 
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $vec = ($params) ? $usu->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos vacíos"]; 
            break;
        case 'eliminar': 
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            $vec = $usu->eliminar($id); 
            break;
        case 'filtro': 
            $dato = $_GET['dato'] ?? '';
            $vec = $usu->filtro($dato); 
            break;
        default: 
            $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
    }
} catch (Exception $e) {
    // Si algo falla, el error se convierte en un JSON válido que Angular puede entender
    $vec = ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
}

// 2. LIMPIEZA: Asegurar que no haya basura en el buffer
if (ob_get_length()) ob_clean();

echo json_encode($vec);
exit; // Finalizar ejecución inmediatamente
?>