<?php
class Conexion {
    public static function conectar() {
        try {
            $conn = new PDO(
                "mysql:host=mysql-3baa13e1-cengicana1.e.aivencloud.com;port=11821;dbname=laboratorios_prueba;charset=utf8mb4",
                "avnadmin",
                "AVNS_TEwBa_lAauoYBxqVGh9"
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $conn;
        } catch (PDOException $e) {
            throw new RuntimeException("Error de conexión: " . $e->getMessage());
        }
    }
}

$conexion = Conexion::conectar();
?>
