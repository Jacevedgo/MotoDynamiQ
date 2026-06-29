<?php
class Usuarios {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function login($params) {
        $sql = "SELECT id, nombre, identificacion, rol FROM usuarios WHERE identificacion = ? AND password = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $params->identificacion, $params->password);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($res)) {
            return ["Resultado" => "OK", "id" => $row['id'], "identificacion" => $row['identificacion'], "nombre" => $row['nombre'], "rol" => $row['rol']];
        }
        return ["Resultado" => "ERROR", "Mensaje" => "Credenciales incorrectas"];
    }

    public function consulta() {
        $sql = "SELECT id, identificacion, nombre, rol, password FROM usuarios ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO usuarios (nombre, identificacion, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->identificacion, $params->password, $params->rol);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Usuario registrado"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE usuarios SET nombre=?, identificacion=?, password=?, rol=? WHERE id=?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $params->nombre, $params->identificacion, $params->password, $params->rol, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Usuario actualizado"];
    }

    // MÉTODO FALTANTE AÑADIDO AQUÍ
    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Usuario eliminado"];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: El usuario tiene ventas o compras registradas."];
        }
    }
}
?>