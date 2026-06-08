<?php
class Clientes {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todos los clientes ordenados alfabéticamente
    public function consulta() {
        $sql = "SELECT * FROM clientes ORDER BY nombre ASC";
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

    // Insertar un nuevo cliente validando campos opcionales (Seguro)
    public function insertar($params) {
        $sql = "INSERT INTO clientes(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // 🛡️ Validación de consistencia para valores nulos nativos
        $nombre    = isset($params->nombre) ? trim($params->nombre) : '';
        $telefono  = !empty($params->telefono) ? trim($params->telefono) : null;
        $email     = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : 'No especificada';

        mysqli_stmt_bind_param($stmt, "ssss", $nombre, $telefono, $email, $direccion);
        
        if (mysqli_stmt_execute($stmt)) {
            return [
                "Resultado" => "OK",
                "Mensaje" => "El cliente ha sido insertado con éxito."
            ];
        } else {
            return [
                "Resultado" => "ERROR",
                "Mensaje" => mysqli_stmt_error($stmt)
            ];
        }
    }

    // 📝 EDITAR: Actualiza los datos desde la modal flotante usando Prepared Statements
    public function editar($id, $params) {
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // 🛡️ Mapeo y limpieza idéntica para resguardar la consistencia
        $nombre    = isset($params->nombre) ? trim($params->nombre) : '';
        $telefono  = !empty($params->telefono) ? trim($params->telefono) : null;
        $email     = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : 'No especificada';

        mysqli_stmt_bind_param($stmt, "ssssi", $nombre, $telefono, $email, $direccion, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return [
                "Resultado" => "OK",
                "Mensaje" => "El cliente ha sido actualizado con éxito."
            ];
        } else {
            return [
                "Resultado" => "ERROR",
                "Mensaje" => mysqli_stmt_error($stmt)
            ];
        }
    }

    // 🗑️ ELIMINAR: Control estricto de integridad referencial ante ventas activas
    public function eliminar($id) {
    try {
        $sql = "DELETE FROM clientes WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return ["Resultado" => "OK", "Mensaje" => "Cliente eliminado correctamente."];
    } catch (PDOException $e) {
        // Captura el error de llave foránea (1451)
        if ($e->getCode() == 23000) {
            return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: el cliente tiene ventas registradas."];
        }
        return ["Resultado" => "ERROR", "Mensaje" => "Error al eliminar: " . $e->getMessage()];
    }
  }
    // Filtrar clientes dinámicamente por coincidencia en el nombre
    public function filtro($valor) {
        $sql = "SELECT * FROM clientes WHERE nombre LIKE ? ORDER BY nombre ASC";
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