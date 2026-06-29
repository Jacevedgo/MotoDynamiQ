<?php
class Categoria {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT DISTINCT m.marca, m.modelo, c.nombre AS categoria, pr.nombre AS proveedor
                FROM motocicletas m
                INNER JOIN categoria c ON m.fo_categoria = c.id_categoria
                INNER JOIN detalle_compras dc ON m.id = dc.motocicleta_id
                INNER JOIN compras co ON dc.compra_id = co.id
                INNER JOIN proveedores pr ON co.proveedor_id = pr.id
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

    // Eliminar categoría
    public function eliminar($id) {
        $sql = "DELETE FROM categoria WHERE id_categoria = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La categoría ha sido eliminada"
        ];
    }

    // Insertar categoría
    public function insertar($params) {
        $sql = "INSERT INTO categoria(nombre) VALUES (?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $params->nombre);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La categoría ha sido insertada"
        ];
    }

    // Editar categoría
    public function editar($id, $params) {
        $sql = "UPDATE categoria SET nombre = ? WHERE id_categoria = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "si", $params->nombre, $id);
        mysqli_stmt_execute($stmt);

        return [
            "Resultado" => "OK",
            "Mensaje" => "La categoría ha sido actualizada"
        ];
    }

    // Filtrar categorías por nombre
    public function filtro($valor) {
        $sql = "SELECT * FROM categoria WHERE nombre LIKE ?";
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
    // ... mantén tus otros métodos (insertar, eliminar, etc.) aquí ...
}
?>