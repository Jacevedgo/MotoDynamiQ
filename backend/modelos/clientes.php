<?php
class Clientes {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT * FROM clientes ORDER BY nombre ASC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO clientes(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $nombre = trim($params->nombre ?? '');
        $telefono = !empty($params->telefono) ? trim($params->telefono) : null;
        $email = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : 'No especificada';

        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $telefono, $email, $direccion);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Cliente insertado con éxito."];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $nombre = trim($params->nombre ?? '');
        $telefono = !empty($params->telefono) ? trim($params->telefono) : null;
        $email = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : 'No especificada';

        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $telefono, $email, $direccion, $id);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Cliente actualizado correctamente"]; // CORREGIDO
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Cliente eliminado correctamente."];
        } else {
            // Si hay error (ej. llave foránea), mysqli devuelve el error
            return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: tiene ventas registradas."];
        }
    }

    public function filtro($valor) {
        $sql = "SELECT * FROM clientes WHERE nombre LIKE ? ORDER BY nombre ASC";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "s", $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>