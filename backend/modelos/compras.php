<?php
class Compras {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre_usuario AS usuario, co.total 
                FROM compras co 
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id 
                INNER JOIN usuarios u ON co.usuario_id = u.id";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $stmt = mysqli_prepare($this->conexion, "INSERT INTO compras (fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }

    public function editar($id, $params) {
        $stmt = mysqli_prepare($this->conexion, "UPDATE compras SET fecha = ?, proveedor_id = ?, total = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sidi", $params->fecha, $params->proveedor_id, $params->total, $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR"];
    }

    public function eliminar($id) {
        $stmt = mysqli_prepare($this->conexion, "DELETE FROM compras WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR"];
    }
}
?>