<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

$control = isset($_GET['control']) ? $_GET['control'] : '';
$com = new Compras($conexion);
$vec = [];

$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $com->consulta();
        break;
    case 'insertar':
        $vec = ($params) ? $com->insertar($params) : ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        break;
    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $com->eliminar($id);
        break;
    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $com->filtro($dato);
        break;
    default:
        $vec = ["Resultado" => "ERROR", "Mensaje" => "Acción no válida"];
        break;
}

echo json_encode($vec);
?>