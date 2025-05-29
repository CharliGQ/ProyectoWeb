<?php

class AuthController {
    private $model;

    public function __construct() {
        try {
            require_once 'app/models/UserModel.php';
            $this->model = new UserModel();
        } catch (Exception $e) {
            $error = "Error de conexión: " . $e->getMessage();
            require_once 'app/views/auth/index.php';
            exit;
        }
    }

    public function index() {
        require_once 'app/views/auth/index.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'];
            $contrasenia = $_POST['contrasenia'];

            $usuario = $this->model->login($correo, $contrasenia);
            
            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = $usuario;
                header('Location: index.php?controller=home&action=index');
                exit;
            } else {
                $error = "Credenciales inválidas";
                require_once 'app/views/auth/login.php';
            }
        } else {
            require_once 'app/views/auth/login.php';
        }
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $nombre = trim($_POST['nombre_usuario']);
                $correo = trim($_POST['correo']);
                $contrasenia = $_POST['contrasenia'];
                $rol = 'registrado'; // Rol por defecto

                // Validaciones básicas
                if (empty($nombre) || empty($correo) || empty($contrasenia)) {
                    throw new Exception("Todos los campos son obligatorios");
                }

                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("El formato del correo electrónico no es válido");
                }

                if (strlen($contrasenia) < 6) {
                    throw new Exception("La contrasenia debe tener al menos 6 caracteres");
                }

                $contrasenia = password_hash($contrasenia, PASSWORD_DEFAULT);

                if ($this->model->registrarUsuario($nombre, $correo, $contrasenia, $rol)) {
                    header('Location: index.php?controller=auth&action=login');
                    exit;
                }
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once 'app/views/auth/signup.php';
            }
        } else {
            require_once 'app/views/auth/signup.php';
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit;
    }
}