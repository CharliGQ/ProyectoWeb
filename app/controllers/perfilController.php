<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../models/perfilModel.php';

class PerfilController {
    private $crud;

    public function __construct() {
        $this->crud = new CrudPerfil();
    }

    public function obtenerPerfil() {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        $perfil = $this->crud->obtenerPerfil($_SESSION['usuario']['id']);
        if ($perfil) {
            return ['success' => true, 'perfil' => $perfil];
        }
        return ['success' => false, 'message' => 'Error al obtener el perfil'];
    }

    public function actualizarPerfil() {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $perfil = new Perfil();
            $perfil->setIdUsuario($_SESSION['usuario']['id']);
            $perfil->setNombreUsuario($_POST['nombre_usuario']);
            $perfil->setCorreo($_POST['correo']);

            if ($this->crud->actualizarPerfil($perfil)) {
                // Actualizar datos de sesión
                $_SESSION['usuario']['nombre'] = $perfil->getNombreUsuario();
                return ['success' => true, 'message' => 'Perfil actualizado exitosamente'];
            }
            return ['success' => false, 'message' => 'Error al actualizar el perfil'];
        }
        return ['success' => false, 'message' => 'Método no permitido'];
    }

    public function actualizarContrasenia() {
        if (!isset($_SESSION['usuario'])) {
            return ['success' => false, 'message' => 'Usuario no autenticado'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nueva_contrasenia = $_POST['nueva_contrasenia'];
            $confirmar_contrasenia = $_POST['confirmar_contrasenia'];

            if ($nueva_contrasenia !== $confirmar_contrasenia) {
                return ['success' => false, 'message' => 'Las contraseñas no coinciden'];
            }

            if ($this->crud->actualizarContrasenia($_SESSION['usuario']['id'], $nueva_contrasenia)) {
                return ['success' => true, 'message' => 'Contraseña actualizada exitosamente'];
            }
            return ['success' => false, 'message' => 'Error al actualizar la contraseña'];
        }
        return ['success' => false, 'message' => 'Método no permitido'];
    }
}

// Manejo de peticiones
$perfilController = new PerfilController();

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];

    switch ($action) {
        case 'obtener':
            echo json_encode($perfilController->obtenerPerfil());
            break;
        case 'actualizar':
            echo json_encode($perfilController->actualizarPerfil());
            break;
        case 'actualizarContrasenia':
            echo json_encode($perfilController->actualizarContrasenia());
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
    }
}
?> 