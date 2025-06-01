<?php
class Usuario {
    private $id_usuario;
    private $nombre_usuario;
    private $correo;
    private $contrasenia;
    private $fecha_registro;
    private $rol;

    public function __construct() {}

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    public function getNombreUsuario() {
        return $this->nombre_usuario;
    }

    public function setNombreUsuario($nombre_usuario) {
        $this->nombre_usuario = $nombre_usuario;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function getContrasenia() {
        return $this->contrasenia;
    }

    public function setContrasenia($contrasenia) {
        $this->contrasenia = $contrasenia;
    }

    public function getFechaRegistro() {
        return $this->fecha_registro;
    }

    public function setFechaRegistro($fecha_registro) {
        $this->fecha_registro = $fecha_registro;
    }

    public function getRol() {
        return $this->rol;
    }

    public function setRol($rol) {
        $this->rol = $rol;
    }
}
?> 