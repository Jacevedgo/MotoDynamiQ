<?php
class Usuarios {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        $sql = "SELECT id, identificacion, nombre_completo, nombre_usuario, rol FROM usuarios";
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        // Validación básica
        if (empty($params->nombre_completo) || empty($params->nombre_usuario)) {
            return ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        }

        $passwordDefault = "123456"; // Contraseña por defecto
        $sql = "INSERT INTO usuarios (identificacion, nombre_completo, nombre_usuario, password, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssss", 
            $params->identificacion, 
            $params->nombre_completo, 
            $params->nombre_usuario, 
            $passwordDefault, 
            $params->rol
        );
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Usuario registrado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE usuarios SET identificacion = ?, nombre_completo = ?, nombre_usuario = ?, rol = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $params->identificacion, 
            $params->nombre_completo, 
            $params->nombre_usuario, 
            $params->rol, 
            $id
        );
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Usuario actualizado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Usuario eliminado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function filtro($valor) {
        $sql = "SELECT id, identificacion, nombre_completo, nombre_usuario, rol 
                FROM usuarios 
                WHERE nombre_completo LIKE ? OR nombre_usuario LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>