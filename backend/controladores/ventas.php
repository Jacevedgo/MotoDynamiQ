<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/ventas.php'); // Corregido el nombre del archivo

$control = isset($_GET['control']) ? $_GET['control'] : '';
$ven = new Ventas($conexion); // Corregido: Clase Ventas
$vec = [];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $ven->consulta();
        break;
    case 'insertar':
        $vec = ($params) ? $ven->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        break;
    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = ($params) ? $ven->editar($id, $params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        break;
    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $ven->eliminar($id);
        break;
    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $ven->filtro($dato);
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
        break;
}

echo json_encode($vec);
?>