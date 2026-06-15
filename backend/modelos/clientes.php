<?php
class Clientes {
    private $conexion;

    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $res = mysqli_query($this->conexion, "SELECT * FROM clientes ORDER BY nombre ASC");
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $stmt = mysqli_prepare($this->conexion, "INSERT INTO clientes(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->telefono, $params->email, $params->direccion);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK", "Mensaje" => "Cliente insertado"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function editar($id, $params) {
        $stmt = mysqli_prepare($this->conexion, "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $params->nombre, $params->telefono, $params->email, $params->direccion, $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK", "Mensaje" => "Cliente actualizado"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function eliminar($id) {
        $stmt = mysqli_prepare($this->conexion, "DELETE FROM clientes WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Cliente eliminado"];
        } else {
            return (mysqli_errno($this->conexion) == 1451) 
                ? ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: tiene ventas asociadas."]
                : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
        }
    }

    public function filtro($valor) {
        $stmt = mysqli_prepare($this->conexion, "SELECT * FROM clientes WHERE nombre LIKE ? ORDER BY nombre ASC");
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