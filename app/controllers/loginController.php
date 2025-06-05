<?php
require_once(__DIR__ . '../../config/database.php');
require_once(__DIR__ . '../../models/signupModel.php');
require_once(__DIR__ . '/signupController.php');

class LoginController {
    private $crud;

    public function __construct() {
        $this->crud = new CrudUsuario();
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            
            $correo = trim($_POST['email']);
            $contrasenia = trim($_POST['password']);

            $usuario = $this->crud->verificarUsuarioPorCorreo($correo, $contrasenia);

            if ($usuario) {
                // Verificar si el usuario está sancionado
                if ($usuario && $usuario->getSancionado() == 1) {
    session_start();
    $_SESSION['error'] = "Tu cuenta ha sido suspendida. Contacta a un administrador.";
    header('Location: ../views/login.php');
    exit();
}


                // Guardar datos en la sesión
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
                        $_SESSION['error'] = "Rol no válido. Contacta a soporte.";
                        header('Location: ../views/login.php');
                }
                exit();
            } else {
                $_SESSION['error'] = "Credenciales inválidas.";
                header('Location: ../views/login.php');
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();     
        session_destroy();   

        header('Location: ../views/login.php');
        exit();
    }
}

// Determinar acción desde GET o POST
$accion = $_GET['action'] ?? $_POST['action'] ?? 'login';

// Iniciar controlador
$loginController = new LoginController();

switch ($accion) {
    case 'login':
        $loginController->login();
        break;
    case 'logout':
        $loginController->logout();
        break;
    default:
        header('Location: ../views/login.php');
        exit();
}
?>
