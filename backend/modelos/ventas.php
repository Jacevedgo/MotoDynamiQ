<?php
class Ventas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        // Corregido: Referencia a 'u.nombre_usuario' y tabla 'ventas'
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre_usuario AS usuario, v.total
                FROM ventas v
                INNER JOIN clientes c ON v.cliente_id = c.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY v.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO ventas(fecha, cliente_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Venta registrada correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE ventas SET fecha = ?, cliente_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Venta actualizada"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // Se eliminan los detalles primero para mantener integridad referencial
            mysqli_query($this->conexion, "DELETE FROM detalle_venta WHERE venta_id = $id");
            mysqli_query($this->conexion, "DELETE FROM ventas WHERE id = $id");
            
            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Venta eliminada correctamente"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
        }
    }

    public function filtro($valor) {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre_usuario AS usuario, v.total
                FROM ventas v
                INNER JOIN clientes c ON v.cliente_id = c.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                WHERE c.nombre LIKE ?
                ORDER BY v.fecha DESC";
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