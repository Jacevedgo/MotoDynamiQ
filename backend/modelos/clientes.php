<?php
class Clientes {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todos los clientes
    public function consulta() {
        $sql = "SELECT * FROM clientes ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) {
            die("Error en consulta: " . mysqli_error($this->conexion));
        }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    // Insertar cliente
    public function insertar($params) {
        $sql = "INSERT INTO clientes(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->telefono, $params->email, $params->direccion);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El cliente ha sido insertado"
        ];
    }

    // Editar cliente
    public function editar($id, $params) {
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $params->nombre, $params->telefono, $params->email, $params->direccion, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El cliente ha sido actualizado"
        ];
    }

     // Editar Eliminar

    public function eliminar($id) {
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    try {
        mysqli_stmt_execute($stmt);
        return [
            "Resultado" => "OK",
            "Mensaje" => "El cliente ha sido eliminado"
        ];
    } catch (mysqli_sql_exception $e) {
        return [
            "Resultado" => "ERROR",
            "Mensaje" => "No se puede eliminar el cliente porque está asociado a ventas"
        ];
    }
}


    // Filtrar clientes por nombre
    public function filtro($valor) {
        $sql = "SELECT * FROM clientes WHERE nombre LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "s", $like);
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
