<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit(0); }

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

$usu = new Usuarios($conexion);
$control = $_GET['control'] ?? '';

// Leemos el JSON solo una vez al principio
$json = file_get_contents('php://input');
$params = json_decode($json);

$vec = []; // Inicializamos la respuesta

switch ($control) {
    case 'consulta': 
        $vec = $usu->consulta(); 
        break;
    case 'insertar': 
        $vec = $usu->insertar($params); 
        break;
    case 'editar':   
        $vec = $usu->editar($_GET['id'] ?? 0, $params); 
        break;
    case 'eliminar': 
        $vec = $usu->eliminar($_GET['id'] ?? 0); 
        break;
    case 'login': 
        $vec = $usu->login($params); 
        break;
    default: 
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"]; 
        break;
}

echo json_encode($vec);
?>