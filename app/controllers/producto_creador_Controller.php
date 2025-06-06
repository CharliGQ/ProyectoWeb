<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Verificar autenticación
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
    header('Location: ../login.php');
    exit();
}

require_once '../models/productoModel.php';

$accion = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($accion) {
    case 'agregar':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        error_log("POST: " . print_r($_POST, true));
        error_log("FILES: " . print_r($_FILES, true));
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $precio = floatval($_POST['precio']);
        $stock = intval($_POST['stock']);
        $id_usuario = $_SESSION['usuario']['id'];

        // Validaciones básicas
        if (empty($nombre) || $precio <= 0 || $stock < 0) {
            die(json_encode(['success' => false, 'message' => 'Datos inválidos']));
        }

        // Manejo de imagen
        $imagen_url = '';
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = basename($_FILES['imagen']['name']);
            $carpetaDestino = '../../uploads/imagenes/';
            $rutaAlmacenada = '../uploads/imagenes/' . $nombreArchivo;

            // Crear carpeta si no existe
            if (!is_dir($carpetaDestino)) {
                mkdir($carpetaDestino, 0777, true);
            }

            // Mover imagen
            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                $imagen_url = $rutaAlmacenada;
            } else {
                die(json_encode(['success' => false, 'message' => 'Error al subir la imagen']));
            }
        }

        // Llamar al modelo
        if (crearProducto($nombre, $descripcion, $precio, $stock, $imagen_url, $id_usuario)) {
            echo json_encode(['success' => true, 'message' => 'Producto creado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el producto']);
        }
    }
    break;

    case 'listar':
        $id_usuario = $_SESSION['usuario']['id'];
        $productos = listarProductosPorCreador($id_usuario);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'productos' => $productos]);
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id_producto = intval($_GET['id']);
            if (eliminarProducto($id_producto)) {
                header('Location: ../../views/dashboard/creador.php');
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el producto']);
            }
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
        break;
}
?>