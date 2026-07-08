<?php
class DetalleVentas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        // CORREGIDO: tabla 'detalle_venta' (singular)
        $sql = "SELECT dv.id, v.fecha, m.marca, m.modelo, dv.cantidad, dv.subtotal, c.nombre AS cliente
                FROM detalle_venta dv
                INNER JOIN ventas v ON dv.venta_id = v.id
                INNER JOIN motocicletas m ON dv.motocicleta_id = m.id
                INNER JOIN clientes c ON v.cliente_id = c.id
                ORDER BY v.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO detalle_venta(venta_id, motocicleta_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $params->venta_id, $params->motocicleta_id, $params->cantidad, $params->subtotal);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de venta registrado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE detalle_venta SET venta_id = ?, motocicleta_id = ?, cantidad = ?, subtotal = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiidi", $params->venta_id, $params->motocicleta_id, $params->cantidad, $params->subtotal, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de venta actualizado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM detalle_venta WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de venta eliminado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function filtro($valor) {
        $sql = "SELECT dv.id, v.fecha, m.marca, m.modelo, dv.cantidad, dv.subtotal, c.nombre AS cliente
                FROM detalle_venta dv
                INNER JOIN ventas v ON dv.venta_id = v.id
                INNER JOIN motocicletas m ON dv.motocicleta_id = m.id
                INNER JOIN clientes c ON v.cliente_id = c.id
                WHERE dv.subtotal LIKE ?";
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