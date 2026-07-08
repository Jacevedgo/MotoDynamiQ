<?php
// Silenciar errores para que no rompan el JSON
error_reporting(0); 
ini_set('display_errors', 0);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=UTF-8');

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

$control = $_GET['control'] ?? '';
$usu = new Usuarios($conexion);
// Inicializar siempre $vec como un array vacío o estructura de error
$vec = ["Resultado" => "ERROR", "Mensaje" => "Acción inválida"];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'login': $vec = $usu->login($params); break;
    case 'consulta': $vec = $usu->consulta(); break;
    case 'insertar': $vec = $usu->insertar($params); break;
    case 'editar': $vec = $usu->editar($_GET['id'] ?? 0, $params); break;
    case 'eliminar': $vec = $usu->eliminar($_GET['id'] ?? 0); break;
}

echo json_encode($vec);
?>