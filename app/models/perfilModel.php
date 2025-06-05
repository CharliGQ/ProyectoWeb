<?php
require_once '../config/database.php';

class Perfil {
    private $id_usuario;
    private $nombre_usuario;
    private $correo;
    private $fecha_registro;
    private $rol;

    public function __construct() {}

    // Getters
    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

    public function getRol() {
        return $this->rol;
    }

    // Setters
    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function setNombreUsuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
}

class CrudPerfil {
    public function __construct() {}

    public function obtenerPerfil($id_usuario) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM usuarios WHERE id_usuario = :id_usuario');
        $select->bindValue('id_usuario', $id_usuario);
        $select->execute();
        $usuario = $select->fetch();

        if ($usuario) {
            $perfil = new Perfil();
            $perfil->setIdUsuario($usuario['id_usuario']);
            $perfil->setNombreUsuario($usuario['nombre_usuario']);
            $perfil->setCorreo($usuario['correo']);
            $perfil->setFechaRegistro($usuario['fecha_registro']);
            $perfil->setRol($usuario['rol']);
            return $perfil;
        }
        return null;
    }

    public function actualizarPerfil($perfil) {
        $db = Db::conectar();
        $actualizar = $db->prepare('UPDATE usuarios SET nombre_usuario = :nombre_usuario, correo = :correo WHERE id_usuario = :id_usuario');
        
        $actualizar->bindValue('id_usuario', $perfil->getIdUsuario());
        $actualizar->bindValue('nombre_usuario', $perfil->getNombreUsuario());
        $actualizar->bindValue('correo', $perfil->getCorreo());
        
        return $actualizar->execute();
    }

    public function actualizarContrasenia($id_usuario, $nueva_contrasenia) {
        $db = Db::conectar();
        $actualizar = $db->prepare('UPDATE usuarios SET contrasenia = :contrasenia WHERE id_usuario = :id_usuario');
        
        $actualizar->bindValue('id_usuario', $id_usuario);
        $actualizar->bindValue('contrasenia', password_hash($nueva_contrasenia, PASSWORD_DEFAULT));
        
        return $actualizar->execute();
    }
}
?> 