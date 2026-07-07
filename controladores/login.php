<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

require_once('../modelos/conexion.php');

$json = file_get_contents('php://input');
$params = json_decode($json);

if (isset($params->identificacion) && isset($params->password)) {
    $identificacion = mysqli_real_escape_string($conexion, $params->identificacion);
    $password = $params->password; // Nota: Si guardas contraseñas hasheadas, usa password_verify()

    $sql = "SELECT id, nombre, rol FROM usuarios WHERE identificacion = '$identificacion' AND password = '$password'";
    $res = mysqli_query($conexion, $sql);

    if ($row = mysqli_fetch_assoc($res)) {
        echo json_encode(["Resultado" => "OK", "Usuario" => $row]);
    } else {
        echo json_encode(["Resultado" => "ERROR", "Mensaje" => "Credenciales incorrectas"]);
    }
} else {
    echo json_encode(["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"]);
}
?>