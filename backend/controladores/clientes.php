<?php
// 1. Enviamos TODOS los encabezados requeridos antes de cualquier salida de texto
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/clientes.php');

// Recibir parámetro de control de forma segura
$control = isset($_GET['control']) ? $_GET['control'] : '';
$vec = []; // Inicializamos la variable para evitar errores de persistencia

$cli = new Clientes($conexion);

// Capturar el JSON real enviado por Angular en las peticiones HTTP POST
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $cli->consulta();
        break;
    
    case 'insertar':
        if ($params) {
            $vec = $cli->insertar($params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para registrar al cliente."];
        }
        break;

    case 'eliminar':
        // Casteamos a entero para evitar inyecciones de código
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $cli->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($params) {
            $vec = $cli->editar($id, $params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para actualizar al cliente."];
        }
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $cli->filtro($dato);
        break;
}

// 2. Codificamos e imprimimos el JSON al final del flujo
echo json_encode($vec);
?>