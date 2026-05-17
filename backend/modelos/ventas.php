<?php
class Ventas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todas las ventas con cliente y usuario
    public function consulta() {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre AS usuario, v.total
                FROM ventas v
                INNER JOIN clientes c ON v.cliente_id = c.id
                INNER JOIN usuarios u ON v.usuario_id = u.id
                ORDER BY v.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) {
            die('Error en consulta: ' . mysqli_error($this->conexion));
        }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    public function insertar($params) {
    try {
        $sql = "INSERT INTO ventas(fecha, cliente_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // Es vital que estos nombres coincidan con el JSON del controlador
        mysqli_stmt_bind_param($stmt, "siid", 
            $params->fecha, 
            $params->cliente_id, 
            $params->usuario_id, 
            $params->total
        );
        
        mysqli_stmt_execute($stmt);
        
        return [
            "Resultado" => "OK",
            "Mensaje" => "La venta ha sido registrada"
        ];
    } catch (mysqli_sql_exception $e) {
        return [
            "Resultado" => "Error",
            "Mensaje" => "Fallo en la base de datos: " . $e->getMessage()
        ];
    }
}

    // Editar venta
    public function editar($id, $params) {
        $sql = "UPDATE ventas SET fecha = ?, cliente_id = ?, usuario_id = ?, total = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siidi", $params->fecha, $params->cliente_id, $params->usuario_id, $params->total, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La venta ha sido actualizada"
        ];
    }

    // Eliminar venta
    public function eliminar($id) {
        $sql = "DELETE FROM ventas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La venta ha sido eliminada"
        ];
    }

    // Filtrar ventas por cliente
    public function filtro($valor) {
        $sql = "SELECT v.id, v.fecha, c.nombre AS cliente, u.nombre AS usuario, v.total
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
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }
}
?>
