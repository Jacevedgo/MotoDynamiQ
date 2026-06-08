<?php
// Encabezados para CORS y JSON
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
header('Content-Type: application/json; charset=utf-8');

require_once('../modelos/conexion.php');
require_once('../modelos/motocicletas.php');

// Recibir parámetro de control de forma segura
$control = isset($_GET['control']) ? $_GET['control'] : '';
$vec = []; 

$mon = new Motocicletas($conexion);

// Capturar el JSON real que envía Angular desde el cuerpo de la petición (HTTP POST)
$json = file_get_contents('php://input');
$params = json_decode($json);

switch ($control) {
    case 'consulta':
        $vec = $mon->consulta();
        break;
    
    case 'insertar':
        if ($params) {
            $vec = $mon->insertar($params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para registrar la motocicleta."];
        }
        break;

    case 'eliminar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $vec = $mon->eliminar($id);
        break;

    case 'editar':
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($params) {
            $vec = $mon->editar($id, $params);
        } else {
            $vec = ["Resultado" => "ERROR", "Mensaje" => "No se recibieron datos para actualizar."];
        }
        break;

    case 'filtro':
        $dato = isset($_GET['dato']) ? $_GET['dato'] : '';
        $vec = $mon->filtro($dato);
        break;

    // 🏷️ CASO EXTRA: Para cargar dinámicamente el select de categorías en el formulario
    case 'categorias':
        $sql = "SELECT id_categoria AS id, nombre FROM categoria ORDER BY nombre";
        $res = mysqli_query($conexion, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        break;
}

// Renderizar la respuesta final al frontend
echo json_encode($vec);
?>