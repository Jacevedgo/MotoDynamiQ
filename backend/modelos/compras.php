<?php
class Compras {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todas las compras
    public function consulta() {
        $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre AS usuario, co.total
                FROM compras co
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
                INNER JOIN usuarios u ON co.usuario_id = u.id
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

    // Insertar compra
    public function insertar($params) {
        $sql = "INSERT INTO compras(fecha, proveedor_id, usuario_id, total) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "siid", $params->fecha, $params->proveedor_id, $params->usuario_id, $params->total);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La compra ha sido registrada"
        ];
    }

    // Editar compra
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

    // Eliminar compra
    // public function eliminar($id) {
    //     $sql = "DELETE FROM compras WHERE id = ?";
    //     $stmt = mysqli_prepare($this->conexion, $sql);
    //     mysqli_stmt_bind_param($stmt, "i", $id);
    //     mysqli_stmt_execute($stmt);

    //     return [
    //         "Resultado" => "OK",
    //         "Mensaje" => "La compra ha sido eliminada"
    //     ];
    // }

    public function eliminar($id) {
    $sql = "DELETE FROM compras WHERE id = ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    try {
        mysqli_stmt_execute($stmt);
        return [
            "Resultado" => "OK",
            "Mensaje" => "La compra ha sido eliminada"
        ];
    } catch (mysqli_sql_exception $e) {
        return [
            "Resultado" => "ERROR",
            "Mensaje" => "No se puede eliminar la compra porque tiene detalles asociados"
        ];
    }
}



    // Filtrar compras por proveedor
    // public function filtro($valor) {
    //     $sql = "SELECT co.id, co.fecha, pr.nombre AS proveedor, u.nombre AS usuario, co.total
    //             FROM compras co
    //             INNER JOIN proveedores pr ON co.proveedor_id = pr.id
    //             INNER JOIN usuarios u ON co.usuario_id = u.id
    //             WHERE pr.nombre LIKE ?
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

      public function filtro($dato) {
      $sql = "SELECT * FROM compras WHERE fecha LIKE ?";
      $stmt = mysqli_prepare($this->conexion, $sql);
      $like = "%".$dato."%";
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
