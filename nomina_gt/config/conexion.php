<?php
class Conexion {
    private static $conexion;

    public static function conectar() {
        $conexion = new mysqli('localhost', 'root', 'ram0101', 'nomina_gt2');
        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }

    public static function cerrar() {
        if (isset(self::$conexion)) {
            self::$conexion->close();
            self::$conexion = null; // <-- Esto es clave
        }
    }

}
?>
