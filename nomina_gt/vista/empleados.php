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

if (isset($_GET["eliminar"])) {
    Empleado::CambiarEstadoABajaEmpleado($_GET["eliminar"]);
    $_SESSION['mensaje'] = "Empleado dado de baja correctamente.";
    header("Location: empleados.php");
    exit;
}

$empleados = Empleado::ObtenerEmpleados();

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
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../Imagen/fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            color: #003366;
        }

        .overlay {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(255, 255, 200, 0.3);
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 1;
            max-width: 95%;
            margin: 30px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.25);
            border: 2px solid #003366;
            overflow-x: auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #003366;
        }

        form {
            margin-bottom: 15px;
            text-align: center;
        }

        form button, form input[type="submit"] {
            background-color: #003366;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover, form input[type="submit"]:hover {
            background-color: #0055a5;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form input[type="date"],
        form select {
            padding: 8px 12px;
            margin: 5px;
            border: 1.5px solid #003366;
            border-radius: 6px;
            width: calc(100% / 4 - 12px);
            max-width: 300px;
            font-size: 1rem;
            color: #003366;
        }

        form input[type="text"]:focus,
        form input[type="email"]:focus,
        form input[type="number"]:focus,
        form input[type="date"]:focus,
        form select:focus {
            outline: none;
            border-color: #0055a5;
            box-shadow: 0 0 5px rgba(0,85,165,0.7);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            margin-top: 2rem;
        }

        th, td {
            border: 1px solid #003366;
            padding: 8px 10px;
            text-align: center;
            vertical-align: middle;
        }

        thead {
            background-color: #003366;
            color: white;
        }

        tbody tr:nth-child(even) {
            background-color: #f0f4f8;
        }

        tbody tr:hover {
            background-color: #d3e0f0;
        }

        .mensaje {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            color: green;
        }

        .error {
            text-align: center;
            margin-bottom: 1rem;
            font-weight: bold;
            color: red;
        }

        a {
            color: #003366;
            text-decoration: none;
            font-weight: 600;
            margin: 0 5px;
            cursor: pointer;
        }
        a:hover {
            text-decoration: underline;
            color: #0055a5;
        }

        @media (max-width: 700px) {
            form input[type="text"],
            form input[type="email"],
            form input[type="number"],
            form input[type="date"],
            form select {
                width: 100%;
                max-width: 100%;
                margin: 6px 0;
                display: block;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">

        <h2><?= $editar ? "Editar Empleado" : "Nuevo Empleado" ?></h2>

        <!-- Botón para volver al dashboard -->
        <form action="dashboard.php" method="get" style="margin-bottom: 15px;">
            <button type="submit">Volver al Dashboard</button>
        </form>

        <?php if (isset($_SESSION['mensaje'])): ?>
            <p class="mensaje"><?= htmlspecialchars($_SESSION['mensaje']) ?></p>
        <?php unset($_SESSION['mensaje']); endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?= htmlspecialchars($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); endif; ?>

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

            <br><br>
            <input type="submit" value="<?= $editar ? "Actualizar Empleado" : "Crear Empleado" ?>">
        </form>

        <h2>Lista de Empleados</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DPI</th>
                    <th>Dirección</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Departamento</th>
                    <th>Salario Base</th>
                    <th>Tipo Pago</th>
                    <th>Fecha Ingreso</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado): ?>
                    <tr>
                        <td><?= $empleado["id_empleado"] ?></td>
                        <td><?= htmlspecialchars($empleado["nombre"]) ?></td>
                        <td><?= htmlspecialchars($empleado["apellido"]) ?></td>
                        <td><?= htmlspecialchars($empleado["dpi"]) ?></td>
                        <td><?= htmlspecialchars($empleado["direccion"]) ?></td>
                        <td><?= htmlspecialchars($empleado["telefono"]) ?></td>
                        <td><?= htmlspecialchars($empleado["correo"]) ?></td>
                        <td><?= htmlspecialchars($empleado["departamento"]) ?></td>
                        <td><?= number_format($empleado["salario_base"], 2) ?></td>
                        <td><?= htmlspecialchars($empleado["tipo_pago"]) ?></td>
                        <td><?= htmlspecialchars($empleado["fecha_ingreso"]) ?></td>
                        <td><?= htmlspecialchars($empleado["estado"]) ?></td>
                        <td>
                            <a href="empleados.php?editar=<?= $empleado["id_empleado"] ?>">Editar</a>
                            <?php if ($empleado["estado"] !== "baja"): ?>
                                |
                                <a href="empleados.php?eliminar=<?= $empleado["id_empleado"] ?>" onclick="return confirm('¿Está seguro de dar de baja este empleado?')">Dar de baja</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($empleados) === 0): ?>
                    <tr><td colspan="13">No hay empleados registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</body>
</html>
