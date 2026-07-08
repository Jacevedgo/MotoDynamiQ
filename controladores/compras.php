<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { exit; }

require_once('../modelos/conexion.php');
require_once('../modelos/compras.php');

$control = $_GET['control'] ?? '';
$com = new Compras($conexion);
$json = file_get_contents('php://input');
$params = json_decode($json);
$vec = [];

switch ($control) {
    case 'consulta': $vec = $com->consulta(); break;
    case 'insertar': $vec = $com->insertar($params); break;
    case 'editar': $vec = $com->editar($_GET['id'] ?? 0, $params); break;
    case 'eliminar': $vec = $com->eliminar($_GET['id'] ?? 0); break;
    case 'filtro': $vec = $com->filtro($_GET['dato'] ?? ''); break;
}
echo json_encode($vec);
?>