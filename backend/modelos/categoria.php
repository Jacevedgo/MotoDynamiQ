<?php
class Categoria {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        $res = mysqli_query($this->conexion, "SELECT id_categoria, nombre FROM categorias ORDER BY nombre ASC");
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $vec[] = $row;
        }
        return $vec;
    }

    public function insertar($params) {
        $stmt = mysqli_prepare($this->conexion, "INSERT INTO categorias(nombre) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $params->nombre);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }

    public function editar($id, $params) {
        $stmt = mysqli_prepare($this->conexion, "UPDATE categorias SET nombre = ? WHERE id_categoria = ?");
        mysqli_stmt_bind_param($stmt, "si", $params->nombre, $id);
        return mysqli_stmt_execute($stmt) ? ["Resultado" => "OK"] : ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
    }

    public function eliminar($id) {
        $stmt = mysqli_prepare($this->conexion, "DELETE FROM categorias WHERE id_categoria = ?");
        mysqli_stmt_bind_param($stmt, "i", $id);
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK"];
        } else {
            // Manejo específico de error 1451 (Integridad Referencial)
            if (mysqli_errno($this->conexion) == 1451) {
                return ["Resultado" => "ERROR", "Mensaje" => "No se puede eliminar: tiene registros asociados."];
            }
            return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];
        }
    }
}
?>