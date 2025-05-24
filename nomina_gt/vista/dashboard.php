<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Sistema de Nómina</title>
</head>
<body>
    <h1>Bienvenido al Sistema de Nómina GT</h1>
    <p>Usuario conectado: <strong><?php echo $_SESSION['usuario']; ?></strong></p>

    <nav>
        <ul>
            <li><a href="empleados.php">Gestión de Empleados</a></li>
            <li><a href="usuarios.php">Gestión de Usuarios</a></li>
            <li><a href="nomina.php">Generar Nómina</a></li>
            <li><a href="Reportes.php">Reportes</a></li>
            <li><a href="liquidacion.php">Liquidaciones</a></li>
            <li><a href="logout.php">Cerrar Sesión</a></li>
        </ul>
    </nav>
</body>
</html>
