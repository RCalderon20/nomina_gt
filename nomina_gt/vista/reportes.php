<?php
require_once '../controlador/ReportesControlador.php';

$rol = $_SESSION['rol'] ?? ''; 
$dashboardUrl = ($rol === 'admin') ? 'dashboard.php' : 'dashboard2.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Empleados - Sistema de Nómina</title>
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
            max-width: 1100px;
            margin: 30px auto 50px auto;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.25);
            border: 2px solid #003366;
            overflow-x: auto;
            text-align: center;
        }
        h1, h2 {
            color: #003366;
            margin-bottom: 1rem;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        select, button {
            padding: 8px 12px;
            font-size: 1rem;
            border-radius: 6px;
            border: 1px solid #003366;
            margin-left: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button {
            background-color: #003366;
            color: white;
            border: none;
        }
        button:hover, select:hover {
            background-color: #0055a5;
            color: white;
            border-color: #0055a5;
        }
        a button {
            padding: 10px 18px;
        }
        table {
            width: 100%;
            margin: 0 auto 40px auto;
            border-collapse: collapse;
            font-size: 0.9rem;
            text-align: center;
        }
        th, td {
            border: 1px solid #003366;
            padding: 8px 10px;
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
        <h1>Reporte de Empleados</h1>

        <form method="GET" action="reportes.php">
            <label for="estado">Filtrar por estado:</label>
            <select name="estado" id="estado">
                <option value="trabajando" <?= ($estado === 'trabajando') ? 'selected' : '' ?>>Trabajando</option>
                <option value="baja" <?= ($estado === 'baja') ? 'selected' : '' ?>>Baja</option>
            </select>
            <button type="submit">Filtrar</button>
        </form>

        <a href="<?= $dashboardUrl ?>"><button type="button">Volver al Dashboard</button></a>

        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Apellido</th><th>DPI</th><th>Departamento</th><th>Estado</th><th>Fecha Ingreso</th><th>Fecha Baja</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($empleados as $empleado): ?>
                <tr>
                    <td><?= $empleado['id_empleado'] ?></td>
                    <td><?= htmlspecialchars($empleado['nombre']) ?></td>
                    <td><?= htmlspecialchars($empleado['apellido']) ?></td>
                    <td><?= htmlspecialchars($empleado['dpi']) ?></td>
                    <td><?= htmlspecialchars($empleado['departamento']) ?></td>
                    <td><?= $empleado['estado'] ?></td>
                    <td><?= $empleado['fecha_ingreso'] ?></td>
                    <td><?= $empleado['fecha_baja'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Tabla de Nómina</h2>
        <form method="GET" action="reportes.php">
            <label for="filtro">Filtrar por:</label>
            <select name="filtro" id="filtro">
                <option value="todos" <?= ($filtro === 'todos') ? 'selected' : '' ?>>Todos</option>
                <option value="comisiones" <?= ($filtro === 'comisiones') ? 'selected' : '' ?>>Con comisiones > 0</option>
            </select>
            <button type="submit">Aplicar filtro</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>ID Nómina</th>
                    <th>ID Empleado</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Departamento</th>
                    <th>Tipo de Pago</th>
                    <th>Salario Base</th>
                    <th>Horas Extra</th>
                    <th>Valor Horas Extra</th>
                    <th>Comisiones</th>
                    <th>Bonificación</th>
                    <th>IGSS</th>
                    <th>ISR</th>
                    <th>Total Devengado</th>
                    <th>Total Descuentos</th>
                    <th>Total Líquido</th>
                    <th>Fecha Generación</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($nomina)): ?>
                    <?php foreach ($nomina as $n): ?>
                        <tr>
                            <td><?= $n['id_nomina'] ?></td>
                            <td><?= $n['id_empleado'] ?></td>
                            <td><?= $n['nombre_empleado'] ?></td>
                            <td><?= $n['apellido_empleado'] ?></td>
                            <td><?= $n['departamento'] ?></td>
                            <td><?= $n['tipo_pago'] ?></td>
                            <td><?= $n['salario_base'] ?></td>
                            <td><?= $n['horas_extra'] ?></td>
                            <td><?= $n['valor_horas_extra'] ?></td>
                            <td><?= $n['comisiones'] ?></td>
                            <td><?= $n['bonificacion'] ?></td>
                            <td><?= $n['igss'] ?></td>
                            <td><?= $n['isr'] ?></td>
                            <td><?= $n['total_devengado'] ?></td>
                            <td><?= $n['total_descuentos'] ?></td>
                            <td><?= $n['total_liquido'] ?></td>
                            <td><?= $n['fecha_generacion'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="17">No hay registros de nómina disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h2>Tabla de Préstamos</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Empleado</th><th>Monto</th><th>Descripción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <td><?= $prestamo['id_prestamo'] ?></td>
                    <td><?= htmlspecialchars($prestamo['nombre_empleado'] . ' ' . $prestamo['apellido_empleado']) ?></td>
                    <td><?= $prestamo['monto'] ?></td>
                    <td><?= htmlspecialchars($prestamo['descripcion']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
