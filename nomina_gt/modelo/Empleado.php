<?php
require_once('../config/Conexion.php');

class Empleado {

    public static function ObtenerEmpleados() {
        $conexion = Conexion::conectar();
        $resultado = $conexion->query("CALL ObtenerEmpleados()");
        $empleados = [];

        while ($fila = $resultado->fetch_assoc()) {
            $empleados[] = $fila;
        }

        $resultado->close();
        $conexion->close();

        return $empleados;
    }

    public static function obtenerEmpleadoPorId($id) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL ObtenerEmpleadoPorId(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $empleado = $resultado->fetch_assoc();

        while ($stmt->more_results() && $stmt->next_result()) {
            $stmt->store_result(); 
        }

        $stmt->close();
        $conexion->close();

        return $empleado;
    }


    public static function CrearEmpleado($datos) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL CrearEmpleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sssssssssss",
            $datos['nombre'],
            $datos['apellido'],
            $datos['dpi'],
            $datos['direccion'],
            $datos['telefono'],
            $datos['correo'],
            $datos['fecha_ingreso'],
            $datos['departamento'],
            $datos['estado'],
            $datos['salario_base'],
            $datos['tipo_pago']
        );
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    public static function ActualizarEmpleado($datos) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL ActualizarEmpleado(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "isssssssssss",
            $datos['id_empleado'],
            $datos['nombre'],
            $datos['apellido'],
            $datos['dpi'],
            $datos['direccion'],
            $datos['telefono'],
            $datos['correo'],
            $datos['fecha_ingreso'],
            $datos['departamento'],
            $datos['estado'],
            $datos['salario_base'],
            $datos['tipo_pago']
        );
        $stmt->execute();

        $stmt->close();
        $conexion->close();
    }

    public static function CambiarEstadoABajaEmpleado($id) {
        $conexion = Conexion::conectar();
        $stmt = $conexion->prepare("CALL CambiarEstadoABajaEmpleado(?)");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $conexion->close();
    }

}
