<?php
require_once '../modelo/Reporte.php';
session_start();

$estado = $_GET['estado'] ?? 'trabajando';
$empleados = Reporte::ObtenerEmpleadosPorEstado($estado);
$filtro = $_GET['filtro'] ?? 'todos';

if ($filtro === 'comisiones') {
    $nomina = Reporte::obtenerNominaConComisiones();
} else {
    $nomina = Reporte::obtenerNomina();
}

$prestamos = Reporte::obtenerPrestamos();
