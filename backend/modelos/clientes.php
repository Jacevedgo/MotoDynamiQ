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
            return ["Resultado" => "ERROR", "Mensaje" => "Error en consulta: " . mysqli_error($this->conexion)];
        }

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    // Insertar un nuevo cliente validando campos opcionales
    public function insertar($params) {
        $sql = "INSERT INTO clientes(nombre, telefono, email, direccion) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar inserción: " . mysqli_error($this->conexion)];
        }

        $nombre    = isset($params->nombre) ? trim($params->nombre) : '';
        $telefono  = !empty($params->telefono) ? trim($params->telefono) : null;
        $email     = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : null; // Alineado a permitir NULL según phpMyAdmin

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

    // Actualiza los datos desde la modal flotante
    public function editar($id, $params) {
        $sql = "UPDATE clientes SET nombre = ?, telefono = ?, email = ?, direccion = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar actualización: " . mysqli_error($this->conexion)];
        }

        $nombre    = isset($params->nombre) ? trim($params->nombre) : '';
        $telefono  = !empty($params->telefono) ? trim($params->telefono) : null;
        $email     = !empty($params->email) ? trim($params->email) : null;
        $direccion = !empty($params->direccion) ? trim($params->direccion) : null;

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

    // 🗑️ ELIMINAR CORREGIDO: Migrado completamente a mysqli para evitar colapsos
    public function eliminar($id) {
        $sql = "DELETE FROM clientes WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);

        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar eliminación: " . mysqli_error($this->conexion)];
        }

        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Cliente eliminado correctamente."];
        } else {
            $error_code = mysqli_errno($this->conexion);
            // Captura el error de llave foránea (1451) ante compras o ventas activas
            if ($error_code == 1451) {
                return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: el cliente tiene transacciones vinculadas en el sistema."];
            }
            return ["Resultado" => "ERROR", "Mensaje" => "Error al eliminar (" . $error_code . "): " . mysqli_error($this->conexion)];
        }
    }

    // Filtrar clientes dinámicamente por coincidencia en el nombre
    public function filtro($valor) {
        $sql = "SELECT * FROM clientes WHERE nombre LIKE ? ORDER BY nombre ASC";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return [];
        }

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