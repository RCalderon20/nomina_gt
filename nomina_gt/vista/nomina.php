<?php
require_once '../controlador/NominaControlador.php';

$mensaje = '';
$empleadoInfo = null;
$resultado = [];
$calculoRealizado = false;

$controlador = new NominaControlador();

if (isset($_POST['buscar_empleado'])) {
    $id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
    if ($id_empleado) {
        $empleadoInfo = $controlador->modelo->obtenerEmpleadoPorId($id_empleado);
        if (!$empleadoInfo) {
            $mensaje = "Empleado no encontrado.";
        }
    }
}

if (isset($_POST['calcular_nomina'])) {
    $id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
    $horas_extra = filter_input(INPUT_POST, 'horas_extra', FILTER_VALIDATE_FLOAT) ?? 0;
    $comisiones = filter_input(INPUT_POST, 'comisiones', FILTER_VALIDATE_FLOAT) ?? 0;
    $prestamo = filter_input(INPUT_POST, 'prestamo', FILTER_VALIDATE_FLOAT) ?? 0;
    $descripcion_prestamo = filter_input(INPUT_POST, 'descripcion_prestamo', FILTER_SANITIZE_STRING);

    $empleadoInfo = $controlador->modelo->obtenerEmpleadoPorId($id_empleado);

    if ($empleadoInfo) {
        $resultado = $controlador->calcularNominaVistaPrevia($id_empleado, $horas_extra, $comisiones, $prestamo);
        $resultado['descripcion_prestamo'] = $descripcion_prestamo;
        $calculoRealizado = true;
    } else {
        $mensaje = "Empleado no encontrado.";
    }
}

if (isset($_POST['guardar_nomina'])) {
    $id_empleado = filter_input(INPUT_POST, 'id_empleado', FILTER_VALIDATE_INT);
    $horas_extra = $_POST['horas_extra'];
    $comisiones = $_POST['comisiones'];
    $prestamo = $_POST['prestamo'];
    $descripcion_prestamo = $_POST['descripcion_prestamo'];

    $empleadoInfo = $controlador->modelo->obtenerEmpleadoPorId($id_empleado);

    if ($empleadoInfo) {
        $resultado = $controlador->procesarNomina($id_empleado, $horas_extra, $comisiones, $prestamo, $descripcion_prestamo);
        if (isset($resultado['success'])) {
            $mensaje = $resultado['mensaje'];
            $calculoRealizado = false; // Limpiar cálculo tras guardar
            $resultado = [];
        } else {
            $mensaje = $resultado['error'];
        }
    } else {
        $mensaje = "Empleado no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Calcular y Guardar Nómina</title>
    <br>
    <form action="dashboard.php" method="get">
        <button type="submit">Volver al Dashboard</button>
    </form>
</head>
<body>
    <h1>Calcular y Guardar Nómina</h1>

    <?php if ($mensaje): ?>
        <p style="color:blue;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="id_empleado">ID Empleado:</label>
        <input type="number" name="id_empleado" id="id_empleado" value="<?= $empleadoInfo['id_empleado'] ?? '' ?>" required>
        <button type="submit" name="buscar_empleado">Buscar Empleado</button>
    </form>

    <?php if ($empleadoInfo): ?>
        <h2>Datos del Empleado</h2>
        <p><strong>Nombre:</strong> <?= htmlspecialchars($empleadoInfo['nombre']) ?> <?= htmlspecialchars($empleadoInfo['apellido']) ?></p>
        <p><strong>Departamento:</strong> <?= htmlspecialchars($empleadoInfo['departamento']) ?></p>
        <p><strong>Tipo de Pago:</strong> <?= htmlspecialchars($empleadoInfo['tipo_pago']) ?></p>
        <p><strong>Salario Base:</strong> Q<?= number_format($empleadoInfo['salario_base'], 2) ?></p>

        <form method="post" action="">
            <input type="hidden" name="id_empleado" value="<?= htmlspecialchars($empleadoInfo['id_empleado']) ?>">

            <label for="horas_extra">Horas Extra:</label>
            <input type="number" step="0.01" name="horas_extra" id="horas_extra" value="<?= $resultado['horas_extra'] ?? 0 ?>" min="0">

            <label for="comisiones">Comisiones:</label>
            <input type="number" step="0.01" name="comisiones" id="comisiones" value="<?= $resultado['comisiones'] ?? 0 ?>" min="0">

            <label for="prestamo">Préstamo:</label>
            <input type="number" step="0.01" name="prestamo" id="prestamo" value="<?= $resultado['prestamo'] ?? 0 ?>" min="0">

            <label for="descripcion_prestamo">Descripción Préstamo:</label>
            <input type="text" name="descripcion_prestamo" id="descripcion_prestamo" value="<?= htmlspecialchars($resultado['descripcion_prestamo'] ?? '') ?>">

            <button type="submit" name="calcular_nomina">Calcular Nómina</button>
            <?php if ($calculoRealizado): ?>
                <button type="submit" name="guardar_nomina">Guardar Nómina</button>
            <?php endif; ?>
        </form>

        <?php if ($calculoRealizado && !isset($resultado['error'])): ?>
            <h3>Resultado del Cálculo</h3>
            <ul>
                <li>Salario Base: Q<?= number_format($resultado['salario_base'], 2) ?></li>
                <li>Bonificación: Q<?= number_format($resultado['bonificacion'], 2) ?></li>
                <li>Valor Horas Extra: Q<?= number_format($resultado['valor_horas_extra'], 2) ?></li>
                <li>Comisiones: Q<?= number_format($resultado['comisiones'], 2) ?></li>
                <li>IGSS: Q<?= number_format($resultado['igss'], 2) ?></li>
                <li>ISR: Q<?= number_format($resultado['isr'], 2) ?></li>
                <li>Préstamo: Q<?= number_format($resultado['prestamo'], 2) ?></li>
                <li><strong>Total Devengado:</strong> Q<?= number_format($resultado['total_devengado'], 2) ?></li>
                <li><strong>Total Descuentos:</strong> Q<?= number_format($resultado['total_descuentos'], 2) ?></li>
                <li><strong>Sueldo Líquido:</strong> Q<?= number_format($resultado['total_liquido'], 2) ?></li>
            </ul>
        <?php elseif (isset($resultado['error'])): ?>
            <p style="color:red;"><?= htmlspecialchars($resultado['error']) ?></p>
        <?php endif; ?>
    <?php endif; ?>

</body>
</html>
