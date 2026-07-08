<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

require_once('../modelos/conexion.php');
require_once('../modelos/usuarios.php');

$usu = new Usuarios($conexion);
$control = $_GET['control'] ?? '';

// Leer JSON enviado desde Angular
$json = file_get_contents('php://input');
$params = json_decode($json);

try {
    switch ($control) {
        case 'consulta': echo json_encode($usu->consulta()); break;
        case 'insertar': 
            // Validar que params no sea nulo
            echo json_encode($usu->insertar($params)); 
            break;
        case 'editar': 
            echo json_encode($usu->editar($_GET['id'] ?? 0, $params)); 
            break;
        case 'eliminar': 
            echo json_encode($usu->eliminar($_GET['id'] ?? 0)); 
            break;
        default: echo json_encode(["Resultado" => "ERROR", "Mensaje" => "Control no válido"]);
    }
} catch (Exception $e) {
    // Esto es lo que verás en la pestaña de Red de tu navegador
    echo json_encode(["Resultado" => "ERROR", "Mensaje" => $e->getMessage()]);
}
?>