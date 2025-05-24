<?php
require_once '../config/Conexion.php';

class Usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = Conexion::conectar();
    }

    public function listarUsuarios() {
        $stmt = $this->conexion->prepare("CALL sp_listar_usuarios()");
        $stmt->execute();
        $resultado = $stmt->get_result();
        $stmt->close();

        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $resultado;
    }

    public function obtenerUsuarioPorId($id_usuario) {
        $stmt = $this->conexion->prepare("CALL sp_obtener_usuario_por_id(?)");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $usuario = $resultado->fetch_assoc();
        $stmt->close();

        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $usuario;
    }

    public function crearUsuario($nombre_usuario, $contrasena, $rol) {
        $stmt = $this->conexion->prepare("CALL sp_crear_usuario(?, ?, ?)");
        $stmt->bind_param("sss", $nombre_usuario, $contrasena, $rol);
        $resultado = $stmt->execute();
        $stmt->close();

        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $resultado;
    }

    public function actualizarUsuario($id_usuario, $nombre_usuario, $contrasena, $rol) {
        $stmt = $this->conexion->prepare("CALL sp_actualizar_usuario(?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_usuario, $nombre_usuario, $contrasena, $rol);
        $resultado = $stmt->execute();
        $stmt->close();

        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $resultado;
    }

    public function eliminarUsuario($id_usuario) {
        $stmt = $this->conexion->prepare("CALL sp_eliminar_usuario(?)");
        $stmt->bind_param("i", $id_usuario);
        $resultado = $stmt->execute();
        $stmt->close();

        while ($this->conexion->more_results() && $this->conexion->next_result()) {
            $this->conexion->use_result();
        }

        return $resultado;
    }
}
?>
