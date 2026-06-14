<?php
class Categoria {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        $sql = "SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC";
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    public function insertar($params) {
        if (empty($params->nombre)) return ["Resultado" => "ERROR", "Mensaje" => "Nombre vacío"];
        
        $sql = "INSERT INTO categorias(nombre) VALUES (?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $params->nombre);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Categoría insertada"];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
        }
    }

    public function editar($id, $params) {
        $sql = "UPDATE categorias SET nombre = ? WHERE id_categoria = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "si", $params->nombre, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Categoría actualizada"];
        } else {
            return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($stmt)];
        }
    }

    public function eliminar($id) {
        $sql = "DELETE FROM categorias WHERE id_categoria = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Categoría eliminada"];
        } else {
            if (mysqli_errno($this->conexion) == 1451) {
                return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: tiene motos asociadas."];
            }
            return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
        }
    }

    public function filtro($valor) {
        $sql = "SELECT id_categoria, nombre FROM categorias WHERE nombre LIKE ?";
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