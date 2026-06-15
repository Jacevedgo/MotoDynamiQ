<?php
class Ventas {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre_usuario AS usuario, v.total 
                FROM ventas v 
                INNER JOIN clientes c ON v.cliente_id = c.id 
                INNER JOIN usuarios u ON v.usuario_id = u.id";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO ventas(fecha, cliente_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }

    // --- NUEVA FUNCIÓN AÑADIDA PARA SOLUCIONAR EL ERROR 500 ---
    public function editar($id, $params) {
        $sql = "UPDATE ventas SET fecha = ?, cliente_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sidi", $params->fecha, $params->cliente_id, $params->total, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK"];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
        }
    }

    public function eliminar($id) {
        // Borramos los detalles primero para evitar conflictos de Foreign Key
        mysqli_query($this->conexion, "DELETE FROM detalle_venta WHERE venta_id = $id");
        return mysqli_query($this->conexion, "DELETE FROM ventas WHERE id = $id") 
            ? ["Resultado" => "OK"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }
}
?>