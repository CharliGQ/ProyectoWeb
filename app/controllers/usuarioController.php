<?php
session_start();

// Verificar rol del administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'main_owner') {
    $_SESSION['error'] = "No tienes permiso para realizar esta acción.";
    header('Location: ../login.php');
    exit();
}

require_once(__DIR__ . '/../config/database.php');

// Obtener datos de la solicitud
$accion = $_GET['action'] ?? $_POST['action'] ?? null;
$id_usuario = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : null);

// Validar ID
if (!$id_usuario) {
    $_SESSION['error'] = "Usuario no válido.";
    header('Location: ../views/dashboard/admin.php?seccion=usuarios');
    exit();
}

try {
    $db = Db::conectar();

    // Obtener información del usuario seleccionado
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        $_SESSION['error'] = "Usuario no encontrado.";
        header('Location: ../views/dashboard/admin.php?seccion=usuarios');
        exit();
    }

    // Obtener información del main_owner (el que está haciendo la acción)
    $id_main_owner = $_SESSION['usuario']['id'];

    // Validación: No permitir modificar al main_owner desde aquí
    if ($usuario['id_usuario'] == $id_main_owner) {
        $_SESSION['error'] = "No puedes modificarte a ti mismo desde esta sección.";
        header('Location: ../views/dashboard/admin.php?seccion=usuarios');
        exit();
    }

    switch ($accion) {
        case 'sancionar':
            // Alternar entre 0 y 1
            $nuevoEstado = $usuario['sancionado'] == 1 ? 0 : 1;

            $stmt = $db->prepare("UPDATE usuarios SET sancionado = ? WHERE id_usuario = ?");
            $stmt->execute([$nuevoEstado, $id_usuario]);

            $_SESSION['success'] = "Estado de sanción actualizado correctamente.";
            break;

        case 'cambiar_rol':
            $rol = $_GET['rol'] ?? 'registrado';

            // Validar rol permitido
            $rolesValidos = ['registrado', 'moderador', 'creador'];
            if (!in_array($rol, $rolesValidos)) {
                $_SESSION['error'] = "Rol no válido.";
                break;
            }

            $stmt = $db->prepare("UPDATE usuarios SET rol = ? WHERE id_usuario = ?");
            $stmt->execute([$rol, $id_usuario]);

            $_SESSION['success'] = "Rol del usuario actualizado correctamente.";
            break;

        case 'asignar_moderador':
            $stmt = $db->prepare("UPDATE usuarios SET rol = 'moderador' WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            $_SESSION['success'] = "Usuario asignado como moderador.";
            break;

        default:
            $_SESSION['error'] = "Acción no válida.";
            break;
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Error al procesar la solicitud: " . $e->getMessage();
}

// Redirigir
header('Location: ../views/dashboard/admin.php?seccion=usuarios');
exit();
?>