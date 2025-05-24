<?php
require_once('../config/Conexion.php');

class Reporte {
    public static function ObtenerEmpleadosPorEstado($estado) {
        $conexion = Conexion::conectar(); 
        $stmt = $conexion->prepare("CALL ObtenerEmpleadosPorEstado(?)");
        $stmt->bind_param("s", $estado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $empleados = [];
        while ($fila = $resultado->fetch_assoc()) {
            $empleados[] = $fila;
        }

        $stmt->close(); 
        $conexion->close(); 

        return $empleados;
    }

    public static function obtenerNomina() {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL obtener_nomina()");
        $stmt->execute();
        $resultado = $stmt->get_result();

        $nomina = [];
        while ($fila = $resultado->fetch_assoc()) {
            $nomina[] = $fila;
        }

        return $nomina;
    }
    public static function obtenerNominaConComisiones() {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL obtenerNominaConComisiones()");
        $stmt->execute();
        $resultado = $stmt->get_result();

        $nomina = [];
        while ($fila = $resultado->fetch_assoc()) {
            $nomina[] = $fila;
        }

        $stmt->close();
        $conexion->close();

        return $nomina;
}

    public static function obtenerPrestamos() {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL obtener_prestamos()");
        $stmt->execute();
        $resultado = $stmt->get_result();

        $prestamos = [];
        while ($fila = $resultado->fetch_assoc()) {
            $prestamos[] = $fila;
        }

        return $prestamos;
    }

}
