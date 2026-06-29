<?php
class Compras {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                INNER JOIN usuarios u ON co.usuario_id = u.id
                ORDER BY co.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $stmt = mysqli_prepare($this->conexion, "INSERT INTO compras (fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La compra ha sido registrada"
        ];
    }

    public function editar($id, $params) {
        $sql = "UPDATE compras SET fecha = ?, proveedor_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La compra ha sido actualizada"
        ];
    }

    public function eliminar($id) {
    mysqli_begin_transaction($this->conexion);
    try {
        // A. Buscar los detalles de esta compra para saber qué motos se compraron
        $sqlDetalles = "SELECT motocicleta_id, cantidad FROM detalle_compras WHERE compra_id = ?";
        $stmtD = mysqli_prepare($this->conexion, $sqlDetalles);
        mysqli_stmt_bind_param($stmtD, "i", $id);
        mysqli_stmt_execute($stmtD);
        $resD = mysqli_stmt_get_result($stmtD);

        // B. Revertir el stock en la tabla motocicletas
        $sqlStock = "UPDATE motocicletas SET stock = stock - ? WHERE id = ?";
        $stmtStock = mysqli_prepare($this->conexion, $sqlStock);
        
        while ($det = mysqli_fetch_assoc($resD)) {
            mysqli_stmt_bind_param($stmtStock, "ii", $det['cantidad'], $det['motocicleta_id']);
            mysqli_stmt_execute($stmtStock);
        }

        // C. Borrar los detalles de la compra
        $sqlDelDet = "DELETE FROM detalle_compras WHERE compra_id = ?";
        $stmtDelDet = mysqli_prepare($this->conexion, $sqlDelDet);
        mysqli_stmt_bind_param($stmtDelDet, "i", $id);
        mysqli_stmt_execute($stmtDelDet);

        // D. Finalmente borrar el encabezado de la compra
        $sqlDelEnc = "DELETE FROM compras WHERE id = ?";
        $stmtDelEnc = mysqli_prepare($this->conexion, $sqlDelEnc);
        mysqli_stmt_bind_param($stmtDelEnc, "i", $id);
        mysqli_stmt_execute($stmtDelEnc);

        mysqli_commit($this->conexion);
        return ["Resultado" => "OK", "Mensaje" => "Compra eliminada y stock ajustado correctamente"];
    } catch (Exception $e) {
        mysqli_rollback($this->conexion);
        return ["Resultado" => "ERROR", "Mensaje" => "No se pudo eliminar: " . $e->getMessage()];
    }
  }

    public function filtro($dato) {
        // 🔍 Ajustado con INNER JOINs para que la tabla no pierda los nombres al buscar
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                INNER JOIN usuarios u ON co.usuario_id = u.id
                WHERE pr.nombre LIKE ? OR co.fecha LIKE ?
                ORDER BY co.fecha DESC";
        
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%" . $dato . "%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
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
