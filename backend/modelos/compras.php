<?php
class Compras {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT co.id, co.fecha, co.proveedor_id, co.usuario_id, 
                       pr.nombre AS proveedor, IFNULL(u.nombre, 'Sin Asignar') AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                LEFT JOIN usuarios u ON co.usuario_id = u.id
                ORDER BY co.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO compras(fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Guardado"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE compras SET fecha = ?, proveedor_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Actualizado"];
    }

    public function eliminar($id) {
        mysqli_begin_transaction($this->conexion);
        try {
            // Revertir stock
            $resD = mysqli_query($this->conexion, "SELECT motocicleta_id, cantidad FROM detalle_compra WHERE compra_id = $id");
            while ($det = mysqli_fetch_assoc($resD)) {
                mysqli_query($this->conexion, "UPDATE motocicletas SET stock = stock - {$det['cantidad']} WHERE id = {$det['motocicleta_id']}");
            }
            mysqli_query($this->conexion, "DELETE FROM detalle_compra WHERE compra_id = $id");
            mysqli_query($this->conexion, "DELETE FROM compras WHERE id = $id");
            mysqli_commit($this->conexion);
            return ["Resultado" => "OK", "Mensaje" => "Eliminado"];
        } catch (Exception $e) {
            mysqli_rollback($this->conexion);
            return ["Resultado" => "ERROR", "Mensaje" => $e->getMessage()];
        }
    }
}
?>