<?php
class Motocicletas {
    private $conexion;
    public function __construct($conexion) { $this->conexion = $conexion; }

    public function consulta() {
        $sql = "SELECT m.id, m.marca, m.modelo, m.fo_categoria, m.cilindraje, m.precio, m.stock, c.nombre AS categoria
                FROM motocicletas m
                INNER JOIN categoria c ON m.fo_categoria = c.id_categoria
                ORDER BY m.marca";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function obtenerCategorias() {
        $sql = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre";
        $res = mysqli_query($this->conexion, $sql);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO motocicletas (marca, modelo, fo_categoria, cilindraje, precio, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssiidi", $params->marca, $params->modelo, $params->fo_categoria, $params->cilindraje, $params->precio, $params->stock);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Registrado correctamente"];
    }

    public function editar($id, $params) {
        $sql = "UPDATE motocicletas SET marca=?, modelo=?, fo_categoria=?, cilindraje=?, precio=?, stock=? WHERE id=?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "ssiidii", $params->marca, $params->modelo, $params->fo_categoria, $params->cilindraje, $params->precio, $params->stock, $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Actualizado correctamente"];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM motocicletas WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return ["Resultado" => "OK", "Mensaje" => "Eliminado correctamente"];
    }

    public function filtro($valor) {
        $sql = "SELECT m.*, c.nombre AS categoria FROM motocicletas m INNER JOIN categoria c ON m.fo_categoria = c.id_categoria WHERE m.marca LIKE ? OR m.modelo LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>