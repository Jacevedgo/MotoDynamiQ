<?php
class DetalleVentas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar detalles de ventas
    public function consulta() {
        $sql = "SELECT dv.id, v.fecha, m.marca, m.modelo, dv.cantidad, dv.subtotal, c.nombre AS cliente
                FROM detalle_ventas dv
                INNER JOIN ventas v ON dv.venta_id = v.id
                INNER JOIN motocicletas m ON dv.motocicleta_id = m.id
                INNER JOIN clientes c ON v.cliente_id = c.id
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

    // Insertar detalle de venta
    public function insertar($params) {
        $sql = "INSERT INTO detalle_ventas(venta_id, motocicleta_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $params->venta_id, $params->motocicleta_id, $params->cantidad, $params->subtotal);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de venta ha sido registrado"
        ];
    }

    // Editar detalle de venta
    public function editar($id, $params) {
        $sql = "UPDATE detalle_ventas SET venta_id = ?, motocicleta_id = ?, cantidad = ?, subtotal = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiidi", $params->venta_id, $params->motocicleta_id, $params->cantidad, $params->subtotal, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de venta ha sido actualizado"
        ];
    }

    // Eliminar detalle de venta
    public function eliminar($id) {
        $sql = "DELETE FROM detalle_ventas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de venta ha sido eliminado"
        ];
    }

    // Filtrar detalles por motocicleta
    // public function filtro($valor) {
    //     $sql = "SELECT dv.id, v.fecha, m.marca, m.modelo, dv.cantidad, dv.subtotal, c.nombre AS cliente
    //             FROM detalle_ventas dv
    //             INNER JOIN ventas v ON dv.venta_id = v.id
    //             INNER JOIN motocicletas m ON dv.motocicleta_id = m.id
    //             INNER JOIN clientes c ON v.cliente_id = c.id
    //             WHERE m.marca LIKE ?
    //             ORDER BY v.fecha DESC";
    //     $stmt = mysqli_prepare($this->conexion, $sql);
    //     $like = "%$valor%";
    //     mysqli_stmt_bind_param($stmt, "s", $like);
    //     mysqli_stmt_execute($stmt);
    //     $res = mysqli_stmt_get_result($stmt);

    //     $vec = [];
    //     while ($row = mysqli_fetch_assoc($res)) {
    //         $vec[] = $row;
    //     }
    //     return $vec;
    // }

    public function filtro($valor) {
    $sql = "SELECT dv.id, v.fecha, m.marca, m.modelo, dv.cantidad, dv.subtotal, c.nombre AS cliente
            FROM detalle_ventas dv
            INNER JOIN ventas v ON dv.venta_id = v.id
            INNER JOIN motocicletas m ON dv.motocicleta_id = m.id
            INNER JOIN clientes c ON v.cliente_id = c.id
            WHERE dv.subtotal = ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "d", $valor); // "d" porque subtotal es decimal
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
