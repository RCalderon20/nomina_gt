<?php
require_once('../config/Conexion.php');

class Nomina {
    private $conexion;
    private $conn;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conn = $this->conexion->conectar();
    }

    public function obtenerEmpleadoPorId($id_empleado) {
        $stmt = $this->conn->prepare("CALL sp_obtener_empleado_por_id(?)");
        $stmt->bind_param("i", $id_empleado);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $empleado = $resultado->fetch_assoc();
        $stmt->close();
        $this->conn->next_result(); 
        return $empleado;
    }

    public function guardarNomina($datos) {
        $stmt = $this->conn->prepare("CALL sp_insertar_nomina(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            return false;
        }

        $stmt->bind_param(
            "issssdddddddddd",
            $datos['id_empleado'],
            $datos['nombre_empleado'],
            $datos['apellido_empleado'],
            $datos['departamento'],
            $datos['tipo_pago'],
            $datos['salario_base'],
            $datos['horas_extra'],
            $datos['valor_horas_extra'],
            $datos['comisiones'],
            $datos['bonificacion'],
            $datos['igss'],
            $datos['isr'],
            $datos['total_devengado'],
            $datos['total_descuentos'],
            $datos['total_liquido']
        );

        $resultado = $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $resultado;
    }

    public function obtenerUltimoIdNomina() {
        $query = "SELECT LAST_INSERT_ID() as id_nomina";
        $result = $this->conn->query($query);
        $row = $result->fetch_assoc();
        return $row['id_nomina'];
    }

    public function guardarPrestamo($id_nomina, $monto, $descripcion) {
        $stmt = $this->conn->prepare("CALL sp_insertar_prestamo(?, ?, ?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ids", $id_nomina, $monto, $descripcion);
        $resultado = $stmt->execute();
        $stmt->close();
        $this->conn->next_result();
        return $resultado;
    }
}
