<?php
class DetalleCompras {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        // CORREGIDO: tabla 'detalle_compra' (singular)
        $sql = "SELECT dc.id, co.fecha, m.marca, m.modelo, dc.cantidad, dc.subtotal, pr.nombre AS proveedor
                FROM detalle_compra dc
                INNER JOIN compras co ON dc.compra_id = co.id
                INNER JOIN motocicletas m ON dc.motocicleta_id = m.id
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                ORDER BY co.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO detalle_compra(compra_id, motocicleta_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $params->compra_id, $params->motocicleta_id, $params->cantidad, $params->subtotal);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de compra registrado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE detalle_compra SET compra_id = ?, motocicleta_id = ?, cantidad = ?, subtotal = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiidi", $params->compra_id, $params->motocicleta_id, $params->cantidad, $params->subtotal, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de compra actualizado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM detalle_compra WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Detalle de compra eliminado"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function filtro($valor) {
        $sql = "SELECT d.id, c.fecha, m.marca, m.modelo, d.cantidad, d.subtotal, p.nombre AS proveedor
                FROM detalle_compra d
                INNER JOIN compras c ON d.compra_id = c.id
                INNER JOIN motocicletas m ON d.motocicleta_id = m.id
                INNER JOIN proveedores p ON c.proveedor_id = p.id
                WHERE d.subtotal LIKE ?";
        
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