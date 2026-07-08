<?php
class Compras {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT co.id, co.fecha, co.proveedor_id, co.usuario_id, co.total, 
                       IFNULL(pr.nombre, 'Sin proveedor') AS proveedor, 
                       IFNULL(u.nombre, 'Sin usuario') AS usuario
                FROM compras co
                LEFT JOIN proveedores pr ON co.proveedor_id = pr.id
                LEFT JOIN usuarios u ON co.usuario_id = u.id
                ORDER BY co.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO compras (fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Insertado correctamente"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE compras SET fecha = ?, proveedor_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Actualizado correctamente"];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // Revertir stock (opcional, solo si usas detalle_compra)
            $sqlStock = "UPDATE motocicletas m 
                         JOIN detalle_compra dc ON m.id = dc.motocicleta_id 
                         SET m.stock = m.stock - dc.cantidad 
                         WHERE dc.compra_id = ?";
            $stmtS = mysqli_prepare($this->conexion, $sqlStock);
            mysqli_stmt_bind_param($stmtS, "i", $id);
            mysqli_stmt_execute($stmtS);

            // Eliminar detalles y compra
            mysqli_query($this->conexion, "DELETE FROM detalle_compra WHERE compra_id = $id");
            mysqli_query($this->conexion, "DELETE FROM compras WHERE id = $id");
            
            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Compra eliminada"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => "Error al eliminar"];
        }
    }

    public function filtro($dato) {
        $sql = "SELECT co.id, co.fecha, co.total, pr.nombre AS proveedor
                FROM compras co
                LEFT JOIN proveedores pr ON co.proveedor_id = pr.id
                WHERE pr.nombre LIKE ? OR co.fecha LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$dato%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>