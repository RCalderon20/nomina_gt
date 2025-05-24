<?php
require_once 'config/Conexion.php';

class Login {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function autenticar($usuario, $contrasena) {
        $stmt = $this->conexion->prepare("CALL sp_autenticar_usuario(?, ?)");
        $stmt->bind_param("ss", $usuario, $contrasena);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();

        $stmt->close();
        // Limpiar resultados si hay más de un resultset
        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $usuario;
    }
}
