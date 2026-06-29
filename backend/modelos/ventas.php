<?php
class Ventas {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT v.id, v.fecha, v.cliente_id, v.usuario_id, c.nombre AS cliente, u.nombre AS usuario, v.total
                FROM ventas v
                INNER JOIN clientes c ON v.cliente_id = c.id
                LEFT JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY v.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO ventas(fecha, cliente_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Venta registrada"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE ventas SET fecha = ?, cliente_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Venta actualizada"];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // A. Recuperar productos vendidos para sumar stock de nuevo
            $resD = mysqli_query($this->conexion, "SELECT motocicleta_id, cantidad FROM detalle_venta WHERE venta_id = $id");
            while ($det = mysqli_fetch_assoc($resD)) {
                mysqli_query($this->conexion, "UPDATE motocicletas SET stock = stock + {$det['cantidad']} WHERE id = {$det['motocicleta_id']}");
            }

            // B. Borrar detalles y luego la venta
            mysqli_query($this->conexion, "DELETE FROM detalle_venta WHERE venta_id = $id");
            mysqli_query($this->conexion, "DELETE FROM ventas WHERE id = $id");

            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Venta eliminada y stock restaurado"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
        }
    }

    public function filtro($valor) {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre AS usuario, v.total
                FROM ventas v
                INNER JOIN clientes c ON v.cliente_id = c.id
                LEFT JOIN usuarios u ON v.usuario_id = u.id
                WHERE c.nombre LIKE ? OR v.fecha LIKE ?
                ORDER BY v.fecha DESC";
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