<?php
class Motocicletas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todas las motocicletas con su categoría real
    public function consulta() {
        $sql = "SELECT m.id, m.marca, m.modelo, m.fo_categoria, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                LEFT JOIN categoria c ON m.fo_categoria = c.id_categoria
                ORDER BY m.marca";

        // 🔥 CORREGIDO: Faltaba ejecutar la consulta en la base de datos
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

    // Insertar motocicleta incluyendo stock inicial corregido y validación real
    public function insertar($params) {
        if (empty($params->marca) || empty($params->modelo) || empty($params->fo_categoria)) {
            return ["Resultado" => "ERROR", "Mensaje" => "Marca, modelo y categoría son obligatorios"];
        }

        $sql = "INSERT INTO motocicletas (marca, modelo, fo_categoria, cilindraje, precio, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar inserción: " . mysqli_error($this->conexion)];
        }

        $marca        = trim($params->marca);
        $modelo       = trim($params->modelo);
        $fo_categoria = intval($params->fo_categoria);
        $cilindraje   = !empty($params->cilindraje) ? intval($params->cilindraje) : null;
        $precio       = isset($params->precio) ? $params->precio : 0;
        $stock        = isset($params->stock) ? intval($params->stock) : 0;

        mysqli_stmt_bind_param($stmt, "ssiisi", $marca, $modelo, $fo_categoria, $cilindraje, $precio, $stock);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Motocicleta registrada correctamente"];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al ejecutar: " . mysqli_stmt_error($stmt)];
        }
    }

    // Editar motocicleta al 100% con verificación activa
    public function editar($id, $params) {
        $sql = "UPDATE motocicletas 
                SET marca = ?, modelo = ?, fo_categoria = ?, cilindraje = ?, precio = ?, stock = ? 
                WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar actualización: " . mysqli_error($this->conexion)];
        }

        $marca        = trim($params->marca);
        $modelo       = trim($params->modelo);
        $fo_categoria = intval($params->fo_categoria);
        $cilindraje   = !empty($params->cilindraje) ? intval($params->cilindraje) : null;
        $precio       = isset($params->precio) ? $params->precio : 0;
        $stock        = isset($params->stock) ? intval($params->stock) : 0;

        mysqli_stmt_bind_param($stmt, "ssiisii", $marca, $modelo, $fo_categoria, $cilindraje, $precio, $stock, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "La motocicleta ha sido actualizada con éxito."];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al actualizar: " . mysqli_stmt_error($stmt)];
        }
    }

    // Eliminar motocicleta con try-catch nativo de mysqli_sql_exception
    public function eliminar($id) {
        $sql = "DELETE FROM motocicletas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return ["Resultado" => "ERROR", "Mensaje" => "Error al preparar eliminación: " . mysqli_error($this->conexion)];
        }

        mysqli_stmt_bind_param($stmt, "i", $id);

        try {
            if (mysqli_stmt_execute($stmt)) {
                return ["Resultado" => "OK", "La motocicleta ha sido eliminada"];
            } else {
                return ["Resultado" => "ERROR", "Mensaje" => "No se pudo ejecutar la eliminación."];
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1451) {
                return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar la motocicleta porque está asociada a un historial activo de compras o ventas"];
            }
            return ["Resultado" => "ERROR", "Mensaje" => "Error crítico: " . $e->getMessage()];
        }
    }

    // Filtrar motocicletas por marca o modelo
    public function filtro($valor) {
        $sql = "SELECT m.id, m.marca, m.modelo, m.fo_categoria, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                LEFT JOIN categoria c ON m.fo_categoria = c.id_categoria
                WHERE m.marca LIKE ? OR m.modelo LIKE ?
                ORDER BY m.marca";
        
        // 🔥 CORREGIDO: Faltaba preparar el statement antes de la validación
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        if (!$stmt) {
            return [];
        }

        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
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