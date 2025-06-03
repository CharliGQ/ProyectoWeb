<?php
require_once(__DIR__ . '/../config/database.php');
require_once(__DIR__ . '/../models/signupModel.php');
require_once(__DIR__ . '/signupController.php');

class LoginController {
    private $crud;

    public function __construct() {
        $this->crud = new CrudUsuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['email'];
            $contrasenia = $_POST['password'];

            $usuario = $this->crud->verificarUsuarioPorCorreo($correo, $contrasenia);

            if ($usuario) {
                session_start();
                $_SESSION['usuario'] = [
                    'id' => $usuario->getIdUsuario(),
                    'nombre' => $usuario->getNombreUsuario(),
                    'correo' => $usuario->getCorreo(),
                    'rol' => $usuario->getRol()
                ];
                
                // Redirigir según el rol
                switch($usuario->getRol()) {
                    case 'registrado':
                        header('Location: ../views/dashboard/registrado.php');
                        break;
                    case 'moderador':
                        header('Location: ../views/dashboard/moderador.php');
                        break;
                    case 'creador':
                        header('Location: ../views/dashboard/creador.php');
                        break;
                    case 'main_owner':
                        header('Location: ../views/dashboard/admin.php');
                        break;
                    default:
                        header('Location: ../views/dashboard/login.php');
                }
                exit();
            } else {
                session_start();
                $_SESSION['error'] = "Credenciales inválidas";
                header('Location: ../views/login.php');
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: ../views/login.php');
        exit();
    }
}

$loginController = new LoginController();
$loginController->login();
?> 