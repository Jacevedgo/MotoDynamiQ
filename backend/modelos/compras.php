<?php
class Compras {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        // Corregido: Referencia exacta a la columna 'nombre_usuario'
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre_usuario AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                INNER JOIN usuarios u ON co.usuario_id = u.id
                ORDER BY co.fecha DESC";
            
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO compras(fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Compra registrada correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // Revertir stock
            $sqlDet = "SELECT motocicleta_id, cantidad FROM detalle_compras WHERE compra_id = ?";
            $stmtD = mysqli_prepare($this->conexion, $sqlDet);
            mysqli_stmt_bind_param($stmtD, "i", $id);
            mysqli_stmt_execute($stmtD);
            $resD = mysqli_stmt_get_result($stmtD);

            $sqlStock = "UPDATE motocicletas SET stock = stock - ? WHERE id = ?";
            $stmtStock = mysqli_prepare($this->conexion, $sqlStock);
            while ($det = mysqli_fetch_assoc($resD)) {
                mysqli_stmt_bind_param($stmtStock, "ii", $det['cantidad'], $det['motocicleta_id']);
                mysqli_stmt_execute($stmtStock);
            }

            // Eliminar dependencias y compra
            mysqli_query($this->conexion, "DELETE FROM detalle_compras WHERE compra_id = $id");
            mysqli_query($this->conexion, "DELETE FROM compras WHERE id = $id");

            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Compra eliminada y stock ajustado"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
        }
    }

    public function filtro($dato) {
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre_usuario AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                INNER JOIN usuarios u ON co.usuario_id = u.id
                WHERE pr.nombre LIKE ? OR co.fecha LIKE ?
                ORDER BY co.fecha DESC";
        
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