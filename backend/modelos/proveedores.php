<?php
class Proveedores {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todos los proveedores
    public function consulta() {
        $sql = "SELECT * FROM proveedores ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);

        // Cambia esto en modelos/proveedores.php:
      if (!$res) {
      return []; // En lugar de die()
      }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    // Insertar proveedor
    public function insertar($params) {
        $sql = "INSERT INTO proveedores(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssss", $params->nombre, $params->telefono, $params->email, $params->direccion);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "El proveedor ha sido registrado"
        ];
    }

   // ⚡ MÉTODO EDITAR CORREGIDO: Ahora incluye el campo email
    public function editar($id, $params) {
        $sql = "UPDATE proveedores SET nombre=?, telefono=?, email=?, direccion=? WHERE id=?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // Pasamos 4 strings ("ssss") y 1 entero ("i") para el ID
        mysqli_stmt_bind_param($stmt, "ssssi", 
            $params->nombre, 
            $params->telefono, 
            $params->email, 
            $params->direccion, 
            $id
        );
        mysqli_stmt_execute($stmt);

        return ["Resultado" => "OK", "Mensaje" => "El proveedor ha sido actualizado"];
    }


    public function eliminar($id) {
    $sql = "DELETE FROM proveedores WHERE id = ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);

    try {
        mysqli_stmt_execute($stmt);
        return ["Resultado"=>"OK","Mensaje"=>"Proveedor eliminado"];
    } catch (mysqli_sql_exception $e) {
        return ["Resultado"=>"ERROR","Mensaje"=>"No se puede eliminar el proveedor porque tiene compras asociadas"];
    }
}


    public function filtro($valor) {
    $sql = "SELECT * FROM proveedores 
            WHERE nombre LIKE ? OR telefono LIKE ? OR email LIKE ? OR direccion LIKE ?";
    $stmt = mysqli_prepare($this->conexion, $sql);
    $like = "%".$valor."%";
    mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);
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
