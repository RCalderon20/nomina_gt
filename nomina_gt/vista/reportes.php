<?php
require_once '../controlador/ReportesControlador.php';

$rol = $_SESSION['rol'] ?? ''; 
$dashboardUrl = ($rol === 'admin') ? 'dashboard.php' : 'dashboard2.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h1>Reporte de Empleados</h1>

    <form method="GET" action="reportes.php">
        <label for="estado">Filtrar por estado:</label>
        <select name="estado" id="estado">
            <option value="trabajando" <?= ($estado === 'trabajando') ? 'selected' : '' ?>>Trabajando</option>
            <option value="baja" <?= ($estado === 'baja') ? 'selected' : '' ?>>Baja</option>
        </select>
        <button type="submit">Filtrar</button>
    </form>

    <br>
    <a href="<?= $dashboardUrl ?>">
        <button type="button">Volver al Dashboard</button>
    </a>

    <table border="1" cellpadding="5">
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
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr style="background-color: #f2f2f2;">
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


    <!-- Tabla de Préstamos -->
    <h2>Tabla de Préstamos</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th><th>Empleado</th><th>Monto</th><th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestamos as $prestamo): ?>
                <tr>
                    <td><?= $prestamo['id_prestamo'] ?></td>
                    <td><?= $prestamo['nombre_empleado'] . ' ' . $prestamo['apellido_empleado'] ?></td>
                    <td><?= $prestamo['monto'] ?></td>
                    <td><?= $prestamo['descripcion'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
