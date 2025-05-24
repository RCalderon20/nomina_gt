<?php
require_once '../modelo/Usuario.php';

session_start(); 

$usuarioModel = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    if (isset($_POST['crear'])) {
        if ($nombre_usuario !== '' && $contrasena !== '' && ($rol === 'admin' || $rol === 'rrhh')) {
            $resultado = $usuarioModel->crearUsuario($nombre_usuario, $contrasena, $rol);
            if ($resultado) {
                $_SESSION['mensaje'] = "Usuario creado correctamente.";
            } else {
                $_SESSION['error'] = "Error al crear usuario.";
            }
        } else {
            $_SESSION['error'] = "Todos los campos son obligatorios y rol debe ser admin o rrhh.";
        }
    } elseif (isset($_POST['actualizar'])) {
        $id_usuario = intval($_POST['id_usuario'] ?? 0);
        if ($id_usuario > 0 && $nombre_usuario !== '' && $contrasena !== '' && ($rol === 'admin' || $rol === 'rrhh')) {
            $resultado = $usuarioModel->actualizarUsuario($id_usuario, $nombre_usuario, $contrasena, $rol);
            if ($resultado) {
                $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
            } else {
                $_SESSION['error'] = "Error al actualizar usuario.";
            }
        } else {
            $_SESSION['error'] = "Datos inválidos para actualizar.";
        }
    } elseif (isset($_POST['eliminar'])) {
        $id_usuario = intval($_POST['id_usuario'] ?? 0);
        if ($id_usuario > 0) {
            $resultado = $usuarioModel->eliminarUsuario($id_usuario);
            if ($resultado) {
                $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            } else {
                $_SESSION['error'] = "Error al eliminar usuario.";
            }
        } else {
            $_SESSION['error'] = "ID de usuario inválido para eliminar.";
        }
    }
    header('Location: usuarios.php');
    exit();
}

$usuarios = $usuarioModel->listarUsuarios();

$editar = null;
if (isset($_GET['editar'])) {
    $editar = $usuarioModel->obtenerUsuarioPorId(intval($_GET['editar']));
}
?>
