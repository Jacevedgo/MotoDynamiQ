<?php
class Usuarios {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT id, identificacion, nombre, password, rol FROM usuarios ORDER BY id DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

public function insertar($params) {
    // Si no viene password, asignamos un valor por defecto para que la BD no rechace la inserción
    $pwd = !empty($params->password) ? $params->password : '123';
    
    $sql = "INSERT INTO usuarios (nombre, identificacion, password, rol) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conexion, $sql);
    
    if (!$stmt) {
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }
    
    mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->identificacion, $pwd, $params->rol);
    
    if (mysqli_stmt_execute($stmt)) {
        return ["Resultado" => "OK"];
    } else {
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }
}

    public function editar($id, $params) {
        $sql = "UPDATE usuarios SET nombre=?, identificacion=?, password=?, rol=? WHERE id=?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        // "ssssi" indica 4 strings y 1 integer (id)
        mysqli_stmt_bind_param($stmt, "ssssi", $params->nombre, $params->identificacion, $params->password, $params->rol, $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR"];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR"];
    }
}
?>