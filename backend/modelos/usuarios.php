<?php
class Usuarios {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        $sql = "SELECT id, nombre_completo, nombre_usuario, rol FROM usuarios";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    public function insertar($params) {
        // Usamos los nombres reales de las propiedades que Angular debe enviar
        if (empty($params->nombre_completo) || empty($params->nombre_usuario) || empty($params->rol)) {
            return ["Resultado" => "ERROR", "Mensaje" => "Nombre, usuario y rol son obligatorios"];
        }

        $passwordDefecto = "123456"; 
        $sql = "INSERT INTO usuarios (nombre_completo, nombre_usuario, password, rol) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", 
            $params->nombre_completo, 
            $params->nombre_usuario, 
            $passwordDefecto,
            $params->rol
        );
        mysqli_stmt_execute($stmt);

        return ["Resultado" => "OK", "Mensaje" => "Usuario registrado"];
    }

    public function editar($id, $params) {
        // Ajustamos la consulta UPDATE a los nombres reales
        $sql = "UPDATE usuarios SET nombre_completo=?, nombre_usuario=?, password=?, rol=? WHERE id=?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $params->nombre_completo, 
            $params->nombre_usuario, 
            $params->password, 
            $params->rol, 
            $id
        );
        mysqli_stmt_execute($stmt);

        return ["Resultado"=>"OK","Mensaje"=>"El usuario ha sido actualizado"];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado"=>"OK","Mensaje"=>"Usuario eliminado"];
    }

    public function filtro($valor) {
        $sql = "SELECT id, nombre_completo, nombre_usuario, rol FROM usuarios WHERE nombre_completo LIKE ? OR nombre_usuario LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }
}
?>