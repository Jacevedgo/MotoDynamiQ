<?php
// 1. Encabezados para CORS (Deben ir al puro inicio antes de cualquier respuesta)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/reportes.php');

// Validar que el parámetro control exista para evitar errores
$control = isset($_GET['control']) ? $_GET['control'] : '';

$rep = new Reportes($conexion);
$vec = []; // Inicializar la variable de respuesta

// Capturar el JSON crudo que envía Angular de forma dinámica
$json_recibido = file_get_contents('php://input');
$params = json_decode($json_recibido);

switch ($control) {
    case 'consulta':
        $vec = $rep->consulta();
        break;
        
    case 'insertar':
        // Le pasamos a la clase los parámetros reales capturados de Angular
        $vec = $rep->insertar($params);
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $rep->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $rep->editar($id, $params);
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $rep->filtro($dato);
        break;
        
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Controlador no válido"];
        break;
}

// Imprimir el JSON limpio al final sin interferencias
echo json_encode($vec);
?>