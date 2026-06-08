<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/proveedores.php');

// Recibir parámetro de control de forma segura para evitar advertencias
$control = isset($_GET['control']) ? $_GET['control'] : '';

$pro = new Proveedores($conexion);
$vec = []; // Inicializamos la respuesta vacía

// Capturar el JSON real que envía Angular desde el cuerpo de la petición (HTTP POST)
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $pro->consulta();
        break;
    
    case 'insertar':
        if ($params) {
            $vec = $pro->insertar($params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para insertar."];
        }
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $pro->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($params) {
            $vec = $pro->editar($id, $params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para actualizar."];
        }
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $pro->filtro($dato);
        break;

    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Controlador no reconoce la acción solicitada."];
        break;
}

// Renderizar la respuesta final al frontend
echo json_encode($vec);
?>