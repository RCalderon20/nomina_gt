<?php
session_start(); 

require_once('../modelo/Empleado.php');
if (isset($_SESSION['mensaje'])) {
    echo '<p style="color: green;">' . htmlspecialchars($_SESSION['mensaje']) . '</p>';
    unset($_SESSION['mensaje']);
}
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}

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
        $_SESSION['mensaje'] = "Empleado actualizado correctamente.";
    } else {
        Empleado::CrearEmpleado($datos);
        $_SESSION['mensaje'] = "Empleado creado correctamente.";
    }

    header("Location: empleados.php");
    exit;
}

// Procesar eliminación (cambiar estado a baja)
if (isset($_GET["eliminar"])) {
    Empleado::GestionarEliminacionEmpleado($_GET["eliminar"]);
    $_SESSION['mensaje'] = "Empleado dado de baja correctamente.";
    header("Location: empleados.php");
    exit;
}

// Obtener lista de empleados para mostrar en tabla
$empleados = Empleado::ObtenerEmpleados();

// Si es edición, cargar datos del empleado
$editar = null;
if (isset($_GET["editar"])) {
    $editar = Empleado::obtenerEmpleadoPorId($_GET["editar"]);
}
?>

<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title><?= $editar ? "Editar Empleado" : "Nuevo Empleado" ?></title>
    </head>
    <body>

    <h2><?= $editar ? "Editar Empleado" : "Nuevo Empleado" ?></h2>

    <form action="dashboard.php" method="get" style="margin-bottom: 15px;">
        <button type="submit">Volver al Dashboard</button>
    </form>

    <form method="POST" action="empleados.php">
        <?php if ($editar): ?>
            <input type="hidden" name="id_empleado" value="<?= $editar["id_empleado"] ?>">
        <?php endif; ?>

        <input type="text" name="nombre" placeholder="Nombre" value="<?= htmlspecialchars($editar["nombre"] ?? '') ?>" required>
        <input type="text" name="apellido" placeholder="Apellido" value="<?= htmlspecialchars($editar["apellido"] ?? '') ?>" required>
        <input type="text" name="dpi" placeholder="DPI" value="<?= htmlspecialchars($editar["dpi"] ?? '') ?>" required>
        <input type="text" name="direccion" placeholder="Dirección" value="<?= htmlspecialchars($editar["direccion"] ?? '') ?>">
        <input type="text" name="telefono" placeholder="Teléfono" value="<?= htmlspecialchars($editar["telefono"] ?? '') ?>">
        <input type="email" name="correo" placeholder="Correo" value="<?= htmlspecialchars($editar["correo"] ?? '') ?>">
        <input type="text" name="departamento" placeholder="Departamento" value="<?= htmlspecialchars($editar["departamento"] ?? '') ?>">
        <input type="number" step="0.01" name="salario_base" placeholder="Salario Base" value="<?= htmlspecialchars($editar["salario_base"] ?? '') ?>" required>

        <select name="tipo_pago" required>
            <option value="semanal" <?= (isset($editar["tipo_pago"]) && $editar["tipo_pago"] == "semanal") ? "selected" : "" ?>>Semanal</option>
            <option value="quincenal" <?= (isset($editar["tipo_pago"]) && $editar["tipo_pago"] == "quincenal") ? "selected" : "" ?>>Quincenal</option>
            <option value="mensual" <?= (isset($editar["tipo_pago"]) && $editar["tipo_pago"] == "mensual") ? "selected" : "" ?>>Mensual</option>
        </select>

        <input type="date" name="fecha_ingreso" value="<?= htmlspecialchars($editar["fecha_ingreso"] ?? '') ?>" required>

        <select name="estado" required>
            <option value="trabajando" <?= (isset($editar["estado"]) && $editar["estado"] == "trabajando") ? "selected" : "" ?>>Trabajando</option>
            <option value="baja" <?= (isset($editar["estado"]) && $editar["estado"] == "baja") ? "selected" : "" ?>>Baja</option>
        </select>

        <button type="submit">Guardar</button>
    </form>

    <h2>Listado de Empleados</h2>
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
                <td>
                    <a href="empleados.php?editar=<?= $e["id_empleado"] ?>">Editar</a> |
                    <a href="empleados.php?eliminar=<?= $e["id_empleado"] ?>" onclick="return confirm('¿Eliminar este empleado?')">Eliminar</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    </body>
    </html>
