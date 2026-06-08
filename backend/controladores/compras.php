<?php
// 1. Encabezados para CORS (Deben ir al inicio absoluto antes de imprimir datos)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

// Validar de forma segura que el parámetro control exista
$control = isset($_GET['control']) ? $_GET['control'] : '';

$com = new Compras($conexion);
$vec = []; // Variable limpia para almacenar respuestas

// Capturar el JSON dinámico enviado desde el servicio de Angular
$json_recibido = file_get_contents('php://input');
$params = json_decode($json_recibido);

switch ($control) {
    case 'consulta':
        $vec = $com->consulta();
        break;

    case 'insertar':
        // 🛡️ Validamos que el objeto no esté vacío y contenga los datos mínimos obligatorios de compras
        if ($params && isset($params->fecha) && isset($params->proveedor_id) && isset($params->total)) {
            $vec = $com->insertar($params);
        } else {
            $vec = [
                "Resultado" => "ERROR",
                "Mensaje" => "No se recibieron los datos obligatorios para registrar la compra."
            ];
        }
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $com->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        // Validamos que los parámetros de edición existan antes de procesar
        if ($params) {
            $vec = $com->editar($id, $params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "Faltan datos para editar la compra."];
        }
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $com->filtro($dato);
        break;
        
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida en el controlador de compras"];
        break;
}

// Imprimir el JSON estructurado al final sin interferencias de cabeceras tardías
echo json_encode($vec);
?>