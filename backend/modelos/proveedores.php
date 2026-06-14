<?php
class Proveedores {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        $sql = "SELECT * FROM proveedores ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO proveedores(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->telefono, $params->email, $params->direccion);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Proveedor registrado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE proveedores SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssssi", $params->nombre, $params->telefono, $params->email, $params->direccion, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Proveedor actualizado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM proveedores WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        try {
            mysqli_stmt_execute($stmt);
            return ["Resultado" => "OK", "Mensaje" => "Proveedor eliminado correctamente"];
        } catch (mysqli_sql_exception $e) {
            return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: tiene registros asociados (compras)"];
        }
    }

    public function filtro($valor) {
        $sql = "SELECT * FROM proveedores WHERE nombre LIKE ? OR telefono LIKE ? OR email LIKE ? OR direccion LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>