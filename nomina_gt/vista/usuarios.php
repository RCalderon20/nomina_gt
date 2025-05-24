<?php 
require_once '../controlador/UsuarioControlador.php';
?>

<a href="dashboard.php" style="display: inline-block; margin-bottom: 10px; background-color: #007BFF; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px;">← Volver al Dashboard</a>

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
        <a href="usuarios.php">Cancelar</a>
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
