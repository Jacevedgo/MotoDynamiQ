<?php
$servidor = "localhost";
$usuario  = "root";
$clave    = "";
$db       = "motodynamiq";

// Conectar directamente a la base de datos
$conexion = mysqli_connect($servidor, $usuario, $clave, $db);

// Verificar si la conexión falló
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

mysqli_set_charset($conexion, "utf8");

// echo "Se conectó correctamente";
?>