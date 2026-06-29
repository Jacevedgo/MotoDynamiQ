<?php
class Categoria {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        // Consulta limpia a la tabla de categorías
        $sql = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre ASC";
        $res = mysqli_query($this->conexion, $sql);
        
        $vec = [];
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $vec[] = $row;
            }
        }
        return $vec;
    }
    // ... mantén tus otros métodos (insertar, eliminar, etc.) aquí ...
}
?>