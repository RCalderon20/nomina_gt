<?php
require_once 'modelo/Login.php';
session_start();

class LoginControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new Login();
    }

    public function login($usuario, $contrasena) {
        $usuario = trim($usuario);
        $contrasena = trim($contrasena);

        if (!empty($usuario) && !empty($contrasena)) {
            $resultado = $this->modelo->autenticar($usuario, $contrasena);

            if ($resultado) {
                $_SESSION['usuario'] = $usuario;
                $_SESSION['id_usuario'] = $resultado['id_usuario'];
                $_SESSION['rol'] = $resultado['rol']; // Guardamos el rol en sesión

                // Redirigir según el rol
                if ($resultado['rol'] === 'admin') {
                    header('Location: vista/dashboard.php');
                } elseif ($resultado['rol'] === 'rrhh') {
                    header('Location: vista/dashboard2.php');
                } else {
                    $_SESSION['error'] = "Rol no válido.";
                    header('Location: vista/login.php');
                }

                exit();
            } else {
                $_SESSION['error'] = "Credenciales inválidas.";
                header('Location: vista/login.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            header('Location: vista/login.php');
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
