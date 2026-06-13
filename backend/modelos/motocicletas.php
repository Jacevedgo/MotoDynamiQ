<?php
class Motocicletas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todas las motocicletas con su categoría real
    public function consulta() {
        $sql = "SELECT m.id, m.marca, m.modelo, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                INNER JOIN categorias c ON m.fo_categoria = c.id_categoria
                ORDER BY m.marca";
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

    // Insertar motocicleta incluyendo stock inicial corregido
    public function insertar($params) {
        if (empty($params->marca) || empty($params->modelo) || empty($params->fo_categoria)) {
            return ["Resultado" => "ERROR", "Mensaje" => "Marca, modelo y categoría son obligatorios"];
        }

        // Incluimos stock en la inserción
        $sql = "INSERT INTO motocicletas (marca, modelo, fo_categoria, cilindraje, precio, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // s = string, i = entero, d = double (decimal)
        // marca(s), modelo(s), fo_categoria(i), cilindraje(i), precio(d), stock(i) -> "ssiidi"
        mysqli_stmt_bind_param($stmt, "issiidi", 
            $id,
            $params->marca, 
            $params->modelo, 
            $params->fo_categoria, 
            $params->cilindraje, 
            $params->precio, 
            $params->stock
        );
        mysqli_stmt_execute($stmt);

        return ["Resultado" => "OK", "Mensaje" => "Motocicleta registrada correctamente"];
    }

    // Editar motocicleta al 100%
    public function editar($id, $params) {
        $sql = "UPDATE motocicletas 
                SET marca = ?, modelo = ?, fo_categoria = ?, cilindraje = ?, precio = ?, stock = ? 
                WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        
        // marca(s), modelo(s), fo_categoria(i), cilindraje(i), precio(d), stock(i), id(i) -> "ssiidii"
        mysqli_stmt_bind_param($stmt, "ssiidii", 
            $params->marca, 
            $params->modelo, 
            $params->fo_categoria, 
            $params->cilindraje, 
            $params->precio, 
            $params->stock, 
            $id
        );
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La motocicleta ha sido actualizada"
        ];
    }

    // Eliminar motocicleta con try-catch por si tiene ventas/compras amarradas
    public function eliminar($id) {
        $sql = "DELETE FROM motocicletas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);

        try {
            mysqli_stmt_execute($stmt);
            return ["Resultado" => "OK", "Mensaje" => "La motocicleta ha sido eliminada"];
        } catch (mysqli_sql_exception $e) {
            return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar la motocicleta porque está asociada a un historial de compras o ventas"];
        }
    }

    // Filtrar motocicletas por marca o modelo
    public function filtro($valor) {
        $sql = "SELECT m.id, m.marca, m.modelo, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                INNER JOIN categorias c ON m.fo_categoria = c.id_categoria
                WHERE m.marca LIKE ? OR m.modelo LIKE ?
                ORDER BY m.marca";
        $stmt = mysqli_prepare($this->conexion, $sql);
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

    // Nuevo método para obtener categorías
public function obtenerCategorias() {
    $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre";
    $res = mysqli_query($this->conexion, $sql);
    
    $vec = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $vec[] = $row;
    }
    return $vec;
}
}
?>
