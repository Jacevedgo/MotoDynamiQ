<?php
class Usuarios {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    // Consultar todos los usuarios
    public function consulta() {
        $sql = "SELECT id, nombre, usuario, rol FROM usuarios ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) {
            die("Error en consulta: " . mysqli_error($this->conexion));
        }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    // Insertar nuevo usuario
    public function insertar($params) {
    // 🔍 Cambiamos $params->contrasena por $params->rol
    if (empty($params->nombre) || empty($params->usuario) || empty($params->rol)) {
        return ["Resultado" => "ERROR", "Mensaje" => "Todos los campos son obligatorios"];
    }

    // Por ahora, pasamos un valor por defecto para la contraseña en la base de datos
    $contrasenaDefecto = "123456"; 

    $sql = "INSERT INTO usuarios (nombre, usuario, contrasena, rol) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", 
        $params->nombre, 
        $params->usuario, 
        $contrasenaDefecto, // 🔑 Usamos la contraseña por defecto
        $params->rol
    );
    mysqli_stmt_execute($stmt);

    return ["Resultado" => "OK", "Mensaje" => "Usuario registrado"];
}

    // Editar usuario existente
    public function editar($id, $params) {
    // Verificar si el usuario ya existe con otro ID
    $sqlCheck = "SELECT id FROM usuarios WHERE usuario = ? AND id != ?";
    $stmtCheck = mysqli_prepare($this->conexion, $sqlCheck);
    mysqli_stmt_bind_param($stmtCheck, "si", $params->usuario, $id);
    mysqli_stmt_execute($stmtCheck);
    $resCheck = mysqli_stmt_get_result($stmtCheck);

    if (mysqli_num_rows($resCheck) > 0) {
        return ["Resultado"=>"ERROR","Mensaje"=>"El nombre de usuario ya está en uso"];
    }

    // Si no hay duplicado, continuar con la actualización
    $sql = "UPDATE usuarios SET nombre=?, usuario=?, contrasena=?, rol=? WHERE id=?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", 
        $params->nombre, 
        $params->usuario, 
        $params->contrasena, 
        $params->rol, 
        $id
    );
    mysqli_stmt_execute($stmt);

    return ["Resultado"=>"OK","Mensaje"=>"El usuario ha sido actualizado"];
}

    // Eliminar usuario
    public function eliminar($id) {
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    try {
        mysqli_stmt_execute($stmt);
        return ["Resultado"=>"OK","Mensaje"=>"Usuario eliminado"];
    } catch (mysqli_sql_exception $e) {
        return ["Resultado"=>"ERROR","Mensaje"=>"No se puede eliminar el usuario porque tiene compras asociadas"];
    }
  }


    // Filtrar usuarios por nombre o usuario
    public function filtro($valor) {
        $sql = "SELECT id, nombre, usuario, rol FROM usuarios WHERE nombre LIKE ? OR usuario LIKE ?";
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