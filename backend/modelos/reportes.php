<?php
class Reportes {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function consulta() {
        // CORREGIDO: u.nombre_usuario según estructura de tabla usuarios
        $sql = "SELECT r.id, r.titulo, r.descripcion, r.fecha, u.nombre_usuario AS usuario
                FROM reportes r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                ORDER BY r.fecha DESC";
        $res = mysqli_query($this->conexion, $sql);
        if (!$res) return ["Resultado" => "ERROR", "Mensaje" => mysqli_error($this->conexion)];

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }

    public function insertar($params) {
        $sql = "INSERT INTO reportes(titulo, descripcion, fecha, usuario_id) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $params->titulo, $params->descripcion, $params->fecha, $params->usuario_id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Reporte registrado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function editar($id, $params) {
        $sql = "UPDATE reportes SET titulo = ?, descripcion = ?, fecha = ?, usuario_id = ? WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "sssii", $params->titulo, $params->descripcion, $params->fecha, $params->usuario_id, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Reporte actualizado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function eliminar($id) {
        $sql = "DELETE FROM reportes WHERE id = ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if (mysqli_stmt_execute($stmt)) {
            return ["Resultado" => "OK", "Mensaje" => "Reporte eliminado correctamente"];
        }
        return ["Resultado" => "ERROR", "Mensaje" => mysqli_stmt_error($this->conexion)];
    }

    public function filtro($valor) {
        $sql = "SELECT r.id, r.titulo, r.descripcion, r.fecha, u.nombre_usuario AS usuario
                FROM reportes r
                INNER JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.titulo LIKE ?";
        $stmt = mysqli_prepare($this->conexion, $sql);
        $like = "%$valor%";
        mysqli_stmt_bind_param($stmt, "s", $like);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);

        $vec = [];
        while ($row = mysqli_fetch_assoc($res)) { $vec[] = $row; }
        return $vec;
    }
}
?>