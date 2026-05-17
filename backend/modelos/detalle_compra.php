<?php
class DetalleCompras {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar detalles de compras
    public function consulta() {
        $sql = "SELECT dc.id, co.fecha, m.marca, m.modelo, dc.cantidad, dc.subtotal, pr.nombre AS proveedor
                FROM detalle_compras dc
                INNER JOIN compras co ON dc.compra_id = co.id
                INNER JOIN motocicletas m ON dc.motocicleta_id = m.id
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                ORDER BY co.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);

        if (!$res) {
            die("Error en consulta: " . mysqli_error($this->conexion));
        }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    // Insertar detalle de compra
    public function insertar($params) {
        $sql = "INSERT INTO detalle_compras(compra_id, motocicleta_id, cantidad, subtotal) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiid", $params->compra_id, $params->motocicleta_id, $params->cantidad, $params->subtotal);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de compra ha sido registrado"
        ];
    }

    // Editar detalle de compra
    public function editar($id, $params) {
        $sql = "UPDATE detalle_compras SET compra_id = ?, motocicleta_id = ?, cantidad = ?, subtotal = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "iiidi", $params->compra_id, $params->motocicleta_id, $params->cantidad, $params->subtotal, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de compra ha sido actualizado"
        ];
    }

    // Eliminar detalle de compra
    public function eliminar($id) {
        $sql = "DELETE FROM detalle_compras WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El detalle de compra ha sido eliminado"
        ];
    }

    // Filtrar detalles por motocicleta
    // public function filtro($valor) {
    //     $sql = "SELECT dc.id, co.fecha, m.marca, m.modelo, dc.cantidad, dc.subtotal, pr.nombre AS proveedor
    //             FROM detalle_compras dc
    //             INNER JOIN compras co ON dc.compra_id = co.id
    //             INNER JOIN motocicletas m ON dc.motocicleta_id = m.id
    //             INNER JOIN proveedores pr ON co.proveedor_id = pr.id
    //             WHERE m.marca LIKE ?
    //             ORDER BY co.fecha DESC";
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
    $sql = "SELECT d.id, c.fecha, m.marca, m.modelo, d.cantidad, d.subtotal, p.nombre AS proveedor
            FROM detalle_compras d
            INNER JOIN compras c ON d.compra_id = c.id
            INNER JOIN motocicletas m ON d.motocicleta_id = m.id
            INNER JOIN proveedores p ON c.proveedor_id = p.id
            WHERE d.subtotal = ?";  // aquí filtras por subtotal exacto

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
