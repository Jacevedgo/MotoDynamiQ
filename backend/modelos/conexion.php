<?php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";
$db       = "motodynamiq";

// 🔌 Conexión directa pasando la base de datos de una vez
$conexion = mysqli_connect($servidor, $usuario, $clave, $db);

// 🛡️ Si la conexión falla, respondemos en formato JSON para que Angular no se rompa
if (!$conexion) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        "Resultado" => "ERROR",
        "Mensaje" => "Error de conexión a la base de datos: " . mysqli_connect_error()
    ]);
    exit; // Detiene la ejecución de forma limpia
}

// Configurar el set de caracteres para que se muestren bien las tildes y la Ñ
mysqli_set_charset($conexion, "utf8");
?>