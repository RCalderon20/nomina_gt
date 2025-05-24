<?php
require_once('../modelo/Empleado.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $datos = [
        "id_empleado" => $_POST["id_empleado"] ?? null,
        "nombre" => $_POST["nombre"],
        "apellido" => $_POST["apellido"],
        "dpi" => $_POST["dpi"],
        "direccion" => $_POST["direccion"],
        "telefono" => $_POST["telefono"],
        "correo" => $_POST["correo"],
        "departamento" => $_POST["departamento"],
        "salario_base" => $_POST["salario_base"],
        "tipo_pago" => $_POST["tipo_pago"],
        "fecha_ingreso" => $_POST["fecha_ingreso"],
        "estado" => $_POST["estado"]
    ];

    if (!empty($datos["id_empleado"])) {
        Empleado::ActualizarEmpleado($datos);
    } else {
        Empleado::CrearEmpleado($datos);
    }

    header("Location: empleados.php");
    exit;
}

if (isset($_GET["eliminar"])) {
    $idEmpleado = intval($_GET["eliminar"]);

    $resultado = Empleado::GestionarEliminacionEmpleado($idEmpleado);

    session_start();
    if ($resultado === 'Empleado pasado a estado BAJA') {
        $_SESSION['mensaje'] = "Empleado con ID $idEmpleado cambiado a estado BAJA.";
    } elseif ($resultado === 'Empleado eliminado') {
        $_SESSION['mensaje'] = "Empleado con ID $idEmpleado eliminado definitivamente.";
    } else {
        $_SESSION['error'] = "Error: $resultado";
    }

    header("Location: empleados.php");
    exit;
}
