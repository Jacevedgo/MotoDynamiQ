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
    mysqli_begin_transaction($this->conexion);
    try {
        // 1. Opcional: Aquí deberías recuperar el stock y sumarlo a motocicletas
        // ... (lógica de UPDATE a stock) ...

        // 2. Primero borramos los hijos (detalle_ventas)
        $sqlDelDetalles = "DELETE FROM detalle_ventas WHERE venta_id = ?";
        $stmt1 = mysqli_prepare($this->conexion, $sqlDelDetalles);
        mysqli_stmt_bind_param($stmt1, "i", $id);
        mysqli_stmt_execute($stmt1);

        // 3. Luego borramos el padre (ventas)
        $sqlDelVenta = "DELETE FROM ventas WHERE id = ?";
        $stmt2 = mysqli_prepare($this->conexion, $sqlDelVenta);
        mysqli_stmt_bind_param($stmt2, "i", $id);
        mysqli_stmt_execute($stmt2);

        mysqli_commit($this->conexion); // Todo salió bien
        return ["Resultado" => "OK", "Mensaje" => "Venta eliminada correctamente"];
    } catch (Exception $e) {
        mysqli_rollback($this->conexion); // Algo falló, revertimos todo
        return ["Resultado" => "ERROR", "Mensaje" => "Error: " . $e->getMessage()];
    }
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
