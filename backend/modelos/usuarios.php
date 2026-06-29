<?php
class Usuarios {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        // Asegúrate de traer todas las columnas que el HTML espera mostrar
        $sql = "SELECT id, nombre, identificacion AS usuario, 'Sin Rol' AS rol FROM usuarios ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO usuarios (nombre, identificacion, password) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $pass = "123456"; // Password por defecto
        mysqli_stmt_bind_param($stmt, "sss", $params->nombre, $params->usuario, $pass);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Usuario registrado"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE usuarios SET nombre = ?, identificacion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $params->nombre, $params->usuario, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Usuario actualizado"];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Usuario eliminado"];
    }

    // AGREGA ESTE MÉTODO A TU CLASE Usuarios EN modelos/usuarios.php
    public function login($params) {
        // Validar que lleguen los datos correctos
        if (empty($params->identificacion) || empty($params->password)) {
            return ["Resultado" => "ERROR", "Mensaje" => "Datos incompletos"];
        }

        // Consultar usuario por identificación y password exacto (según tu DB)
        $sql = "SELECT id, identificacion, nombre FROM usuarios WHERE identificacion = ? AND password = ? LIMIT 1";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $params->identificacion, $params->password);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($res)) {
            return [
                "Resultado" => "OK",
                "id" => $row['id'],
                "identificacion" => $row['identificacion'],
                "nombre" => $row['nombre']
            ];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => "Identificación o contraseña incorrectos."];
        }
    }
}
?>