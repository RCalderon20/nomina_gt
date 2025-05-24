<?php
require_once 'controlador/LoginControlador.php';

if (isset($_GET['accion']) && $_GET['accion'] === 'login') {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $controlador = new LoginControlador();
    $controlador->login($usuario, $contrasena);
} else {
    header('Location: vista/login.php');
}
