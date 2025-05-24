<?php 
require_once '../controlador/UsuarioControlador.php';
$rol = $_SESSION['rol'] ?? '';
$dashboardUrl = ($rol === 'admin') ? 'dashboard.php' : 'dashboard2.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios</title>
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
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.97);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.25);
            border: 2px solid #003366;
        }

        a.button {
            display: inline-block;
            margin-bottom: 15px;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        input, select, button {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #003366;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #003366;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">

        <!-- Botón de volver dinámico -->
        <a href="<?= $dashboardUrl ?>" class="button">← Volver al Dashboard</a>

        <h2><?= $editar ? "Editar Usuario" : "Crear Nuevo Usuario" ?></h2>

        <form method="post">
            <?php if ($editar): ?>
                <input type="hidden" name="id_usuario" value="<?= $editar['id_usuario'] ?>">
            <?php endif; ?>

            <input name="nombre_usuario" placeholder="Usuario" value="<?= htmlspecialchars($editar['nombre_usuario'] ?? '') ?>" required>
            <input name="contrasena" placeholder="Contraseña" value="<?= htmlspecialchars($editar['contrasena'] ?? '') ?>" required>

            <label for="rol">Rol:</label>
            <select name="rol" required>
                <option value="">Seleccione un rol</option>
                <option value="admin" <?= (isset($editar['rol']) && $editar['rol'] === 'admin') ? 'selected' : '' ?>>admin</option>
                <option value="rrhh" <?= (isset($editar['rol']) && $editar['rol'] === 'rrhh') ? 'selected' : '' ?>>rrhh</option>
            </select>

            <button name="<?= $editar ? 'actualizar' : 'crear' ?>">
                <?= $editar ? 'Actualizar' : 'Crear' ?>
            </button>

            <?php if ($editar): ?>
                <a href="usuarios.php" class="button">Cancelar</a>
            <?php endif; ?>
        </form>
    <h2>Lista de Usuarios</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $usuarios->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
                <td><?= htmlspecialchars($row['rol']) ?></td>
                <td>
                    <a href="usuarios.php?editar=<?= $row['id_usuario'] ?>">Editar</a>
                    |
                    <form method="post" style="display:inline" onsubmit="return confirm('¿Eliminar este usuario?');">
                        <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
                        <button name="eliminar" type="submit">Eliminar</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
</table>
