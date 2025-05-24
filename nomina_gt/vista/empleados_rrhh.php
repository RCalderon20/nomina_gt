<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$rol_usuario = $_SESSION['rol'] ?? null;

require_once('../modelo/Empleado.php');

$empleados = Empleado::ObtenerEmpleados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Empleados - Sistema de Nómina</title>
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

        form button {
            background-color: #003366;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0055a5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
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
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="container">
        <h2>Listado de Empleados</h2>

        <form action="dashboard2.php" method="get">
            <button type="submit">Volver al Dashboard</button>
        </form>

        <table>
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
    </div>
</body>
</html>
