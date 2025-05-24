<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$rol_usuario = $_SESSION['rol'] ?? null;

require_once('../modelo/Empleado.php');

// Solo consultar empleados, no procesar POST ni eliminar ni editar
$empleados = Empleado::ObtenerEmpleados();

?>

<h2>Listado de Empleados</h2>
<!-- Botón para volver al dashboard -->
<form action="dashboard2.php" method="get" style="margin-bottom: 15px;">
    <button type="submit">Volver al Dashboard</button>
</form>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Apellido</th>
        <th>DPI</th>
        <th>Dirección</th>
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Departamento</th>
        <th>Salario Base</th>
        <th>Tipo de Pago</th>
        <th>Fecha de Ingreso</th>
        <th>Estado</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($empleados as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e["nombre"]) ?></td>
            <td><?= htmlspecialchars($e["apellido"]) ?></td>
            <td><?= htmlspecialchars($e["dpi"]) ?></td>
            <td><?= htmlspecialchars($e["direccion"]) ?></td>
            <td><?= htmlspecialchars($e["telefono"]) ?></td>
            <td><?= htmlspecialchars($e["correo"]) ?></td>
            <td><?= htmlspecialchars($e["departamento"]) ?></td>
            <td>Q<?= number_format($e["salario_base"], 2) ?></td>
            <td><?= htmlspecialchars($e["tipo_pago"]) ?></td>
            <td><?= htmlspecialchars($e["fecha_ingreso"]) ?></td>
            <td><?= htmlspecialchars($e["estado"]) ?></td>
            <td>Solo lectura</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
