<?php
class Motocicletas {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Consultar todas las motocicletas con su categoría
    public function consulta() {
        $sql = "SELECT m.id, m.marca, m.modelo, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                INNER JOIN categoria c ON m.fo_categoria = c.id_categoria
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


    public function insertar($params) {
    if (empty($params->marca) || empty($params->modelo) || empty($params->fo_categoria)) {
        return ["Resultado"=>"ERROR","Mensaje"=>"Marca, modelo y categoría son obligatorios"];
    }

    $sql = "INSERT INTO motocicletas (marca, modelo, cilindraje, precio, fo_categoria) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($this->conexion, $sql);
    mysqli_stmt_bind_param($stmt, "ssidi", 
        $params->marca, 
        $params->modelo, 
        $params->cilindraje, 
        $params->precio, 
        $params->fo_categoria
    );
    mysqli_stmt_execute($stmt);

    return ["Resultado"=>"OK","Mensaje"=>"Motocicleta registrada"];
  }



    // Editar motocicleta
    public function editar($id, $params) {
        $sql = "UPDATE motocicletas 
                SET marca = ?, modelo = ?, fo_categoria = ?, cilindraje = ?, precio = ?, stock = ? 
                WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssiidii", $params->marca, $params->modelo, $params->fo_categoria, $params->cilindraje, $params->precio, $params->stock, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La motocicleta ha sido actualizada"
        ];
    }

    // Eliminar motocicleta
    public function eliminar($id) {
        $sql = "DELETE FROM motocicletas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La motocicleta ha sido eliminada"
        ];
    }

    // Filtrar motocicletas por marca o modelo
    public function filtro($valor) {
        $sql = "SELECT m.id, m.marca, m.modelo, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                INNER JOIN categoria c ON m.fo_categoria = c.id_categoria
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
}
?>
