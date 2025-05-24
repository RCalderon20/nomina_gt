<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
session_destroy(); // cerrar sesión anterior si existiera
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Nómina GT</title>
</head>
<body>
    <h2>Login - Nómina GT</h2>
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="../index.php?accion=login" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" name="usuario" required><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>

        <input type="submit" value="Ingresar">
    </form>
</body>
</html>
