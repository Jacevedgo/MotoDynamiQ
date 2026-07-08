<?php
class Ventas {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre AS usuario, v.total
                FROM ventas v
                LEFT JOIN clientes c ON v.cliente_id = c.id
                LEFT JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY v.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        mysqli_begin_transaction($this->conexion);
        try {
            $sql = "INSERT INTO ventas (fecha, cliente_id, usuario_id, total) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($this->conexion, $sql);
            mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total);
            mysqli_stmt_execute($stmt);
            
            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Venta registrada correctamente"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
        }
    }

    public function editar($id, $params) {
        $sql = "UPDATE ventas SET fecha = ?, cliente_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sidi", $params->fecha, $params->cliente_id, $params->total, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Venta actualizada"];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // 1. Revertir stock (sumar stock al eliminar venta)
            $sqlStock = "UPDATE motocicletas m 
                         JOIN detalle_venta dv ON m.id = dv.motocicleta_id 
                         SET m.stock = m.stock + dv.cantidad 
                         WHERE dv.venta_id = ?";
            $stmtS = mysqli_prepare($this->conexion, $sqlStock);
            mysqli_stmt_bind_param($stmtS, "i", $id);
            mysqli_stmt_execute($stmtS);

            // 2. Eliminar detalle y luego venta
            mysqli_query($this->conexion, "DELETE FROM detalle_venta WHERE venta_id = $id");
            mysqli_query($this->conexion, "DELETE FROM ventas WHERE id = $id");
            
            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Venta eliminada correctamente"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => "Error: " . $e->getMessage()];
        }
    }
}
?>