<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=UTF-8');

error_reporting(0); // IMPORTANTE: Oculta errores para no romper el JSON
ini_set('display_errors', 0);

require_once('../modelos/conexion.php');
require_once('../modelos/categoria.php');

$control = $_GET['control'] ?? '';
$cate = new Categoria($conexion);
$vec = [];

if ($control == 'consulta') {
    $vec = $cate->consulta();
}

echo json_encode($vec);
exit();
?>