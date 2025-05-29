<?php

class UserModel {
    private $db;

    public function __construct() {
        try {
            require_once 'app/config/database.php';
            $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }
    }

    public function login($correo, $contrasenia) {
        try {
            $query = "SELECT * FROM usuarios WHERE correo = :correo";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($contrasenia, $usuario['contrasenia'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    public function registrarUsuario($nombre, $correo, $contrasenia, $rol) {
        try {
            // Verificar si el correo ya existe
            $query = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':correo', $correo);
            $stmt->execute();
            
            if ($stmt->fetchColumn() > 0) {
                throw new Exception("El correo electrónico ya está registrado");
            }

            // Insertar nuevo usuario
            $query = "INSERT INTO usuarios (nombre_usuario, correo, contrasenia, rol, fecha_registro) 
                     VALUES (:nombre, :correo, :contrasenia, :rol, NOW())";
            $stmt = $this->db->prepare($query);
            
            $params = array(
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':contrasenia' => $contrasenia,
                ':rol' => $rol
            );

            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error en registro: " . $e->getMessage());
            throw new Exception("Error al registrar el usuario: " . $e->getMessage());
        }
    }
} 