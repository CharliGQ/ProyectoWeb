<?php
require_once('../config/database.php');
require_once('../models/signupModel.php');

class CrudUsuario {
    public function __construct() {}

    // Create
    public function insertar($usuario) {
        $db = Db::conectar();
        $insert = $db->prepare('INSERT INTO usuarios (nombre_usuario, correo, contrasenia, rol) VALUES (:nombre_usuario, :correo, :contrasenia, :rol)');
        $insert->bindValue('nombre_usuario', $usuario->getNombreUsuario());
        $insert->bindValue('correo', $usuario->getCorreo());
        $insert->bindValue('contrasenia', password_hash($usuario->getContrasenia(), PASSWORD_DEFAULT));
        $insert->bindValue('rol', $usuario->getRol());
        $insert->execute();
    }

    // Read
    public function mostrar() {
        $db = Db::conectar();
        $listaUsuarios = [];
        $select = $db->query('SELECT * FROM usuarios');

        foreach ($select->fetchAll() as $usuario) {
            $myUsuario = new Usuario();
            $myUsuario->setIdUsuario($usuario['id_usuario']);
            $myUsuario->setNombreUsuario($usuario['nombre_usuario']);
            $myUsuario->setCorreo($usuario['correo']);
            $myUsuario->setFechaRegistro($usuario['fecha_registro']);
            $myUsuario->setRol($usuario['rol']);
            $listaUsuarios[] = $myUsuario;
        }
        return $listaUsuarios;
    }

    // Update
    public function actualizar($usuario) {
        $db = Db::conectar();
        $actualizar = $db->prepare('UPDATE usuarios SET nombre_usuario=:nombre_usuario, correo=:correo, rol=:rol WHERE id_usuario=:id');
        $actualizar->bindValue('id', $usuario->getIdUsuario());
        $actualizar->bindValue('nombre_usuario', $usuario->getNombreUsuario());
        $actualizar->bindValue('correo', $usuario->getCorreo());
        $actualizar->bindValue('rol', $usuario->getRol());
        $actualizar->execute();
    }

    // Delete
    public function eliminar($id) {
        $db = Db::conectar();
        $eliminar = $db->prepare('DELETE FROM usuarios WHERE id_usuario=:id');
        $eliminar->bindValue('id', $id);
        $eliminar->execute();
    }

    // Search
    public function obtenerUsuario($id) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM usuarios WHERE id_usuario=:id');
        $select->bindValue('id', $id);
        $select->execute();
        $usuario = $select->fetch();
        $myUsuario = new Usuario();
        $myUsuario->setIdUsuario($usuario['id_usuario']);
        $myUsuario->setNombreUsuario($usuario['nombre_usuario']);
        $myUsuario->setCorreo($usuario['correo']);
        $myUsuario->setFechaRegistro($usuario['fecha_registro']);
        $myUsuario->setRol($usuario['rol']);
        return $myUsuario;
    }

    // Login
    public function verificarUsuario($nombre_usuario, $contrasenia) {
        $db = Db::conectar();
        $select = $db->prepare('SELECT * FROM usuarios WHERE nombre_usuario=:nombre_usuario');
        $select->bindValue('nombre_usuario', $nombre_usuario);
        $select->execute();
        $usuario = $select->fetch();
        
        if ($usuario && password_verify($contrasenia, $usuario['contrasenia'])) {
            $myUsuario = new Usuario();
            $myUsuario->setIdUsuario($usuario['id_usuario']);
            $myUsuario->setNombreUsuario($usuario['nombre_usuario']);
            $myUsuario->setCorreo($usuario['correo']);
            $myUsuario->setFechaRegistro($usuario['fecha_registro']);
            $myUsuario->setRol($usuario['rol']);
            return $myUsuario;
        }
        return null;
    }

// Login por correo
    public function verificarUsuarioPorCorreo($correo, $contrasenia) {
    $db = Db::conectar();
    $select = $db->prepare('SELECT * FROM usuarios WHERE correo=:correo');
    $select->bindValue('correo', $correo);
    $select->execute();
    $usuario = $select->fetch();
    
    if ($usuario && password_verify($contrasenia, $usuario['contrasenia'])) {

        $myUsuario = new Usuario();
        $myUsuario->setIdUsuario($usuario['id_usuario']);
        $myUsuario->setNombreUsuario($usuario['nombre_usuario']);
        $myUsuario->setCorreo($usuario['correo']);
        $myUsuario->setFechaRegistro($usuario['fecha_registro']);
        $myUsuario->setRol($usuario['rol']);
        $myUsuario->setSancionado($usuario['sancionado']); 

        // Devuelve el usuario aunque esté sancionado
        return $myUsuario;
    }
    return null;
}
}

?> 