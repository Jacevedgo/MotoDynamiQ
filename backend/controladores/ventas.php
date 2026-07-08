<?php
// 1. Encabezados para CORS (Deben ir al inicio antes de imprimir cualquier respuesta)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php');

// Validar que el parámetro control exista
$control = isset($_GET['control']) ? $_GET['control'] : '';

$vet = new Ventas($conexion);
$vec = []; // Variable para almacenar la respuesta

// Capturar el JSON dinámico enviado desde el servicio de Angular
$json_recibido = file_get_contents('php://input');
$params = json_decode($json_recibido);

switch ($control) {
    case 'consulta':
        $vec = $vet->consulta();
        break;

    case 'insertar':
        // 🛡️ Validamos que el objeto no esté vacío y contenga los datos mínimos obligatorios
        if ($params && isset($params->fecha) && isset($params->total)) {
            $vec = $vet->insertar($params);
        } else {
            $vec = [
                "Resultado" => "ERROR",
                "Mensaje" => "No se recibieron los datos obligatorios para registrar la venta."
            ];
        }
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $vet->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $vet->editar($id, $params);
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $vet->filtro($dato);
        break;
        
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
        break;
}

// Imprimir el JSON estructurado al final sin interferencias
echo json_encode($vec);
?>