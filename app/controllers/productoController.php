<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';
require_once '../models/productoModel.php';

class ProductoController {
    private $crud;

    public function __construct() {
        $this->crud = new CrudProducto();
    }

    public function listarProductos() {
        return $this->crud->mostrar();
    }

    public function listarProductosPorCreador($id_usuario) {
        return $this->crud->mostrarPorCreador($id_usuario);
    }

    public function crearProducto() {
        // Solo permitir a creadores
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
            return ['success' => false, 'message' => 'No autorizado'];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $producto = new Producto();
            $producto->setIdUsuario($_SESSION['usuario']['id']);
            $producto->setNombre($_POST['nombre']);
            $producto->setDescripcion($_POST['descripcion']);
            $producto->setPrecio($_POST['precio']);
            $producto->setStock($_POST['stock']);

            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = basename($_FILES['imagen']['name']);
                $carpetaDestino = '../../uploads/productos/';
                $rutaAlmacenada = '../uploads/productos/' . $nombreArchivo;

                // Crear carpeta si no existe
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                // Mover imagen
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                    $producto->setImagenUrl($rutaAlmacenada);
                } else {
                    return ['success' => false, 'message' => 'Error al subir la imagen'];
                }
            }

            if ($this->crud->insertar($producto)) {
                return ['success' => true, 'message' => 'Producto creado exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al crear el producto'];
            }
        }
        return ['success' => false, 'message' => 'Método no permitido'];
    }

    public function actualizarProducto() {
        // Solo permitir a creadores
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
            return ['success' => false, 'message' => 'No autorizado'];
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $producto = new Producto();
            $producto->setIdProducto($_POST['id_producto']);
            $producto->setNombre($_POST['nombre']);
            $producto->setDescripcion($_POST['descripcion']);
            $producto->setPrecio($_POST['precio']);
            $producto->setStock($_POST['stock']);

            // Manejo de imagen
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = basename($_FILES['imagen']['name']);
                $carpetaDestino = '../../uploads/productos/';
                $rutaAlmacenada = '../uploads/productos/' . $nombreArchivo;

                // Crear carpeta si no existe
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                // Mover imagen
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $carpetaDestino . $nombreArchivo)) {
                    $producto->setImagenUrl($rutaAlmacenada);
                } else {
                    return ['success' => false, 'message' => 'Error al subir la imagen'];
                }
            }

            if ($this->crud->actualizar($producto)) {
                return ['success' => true, 'message' => 'Producto actualizado exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar el producto'];
            }
        }
        return ['success' => false, 'message' => 'Método no permitido'];
    }

    public function eliminarProducto($id) {
        // Solo permitir a creadores
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
            return ['success' => false, 'message' => 'No autorizado'];
        }
        if ($this->crud->eliminar($id)) {
            return ['success' => true, 'message' => 'Producto eliminado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al eliminar el producto'];
        }
    }

    public function obtenerProducto($id) {
        return $this->crud->obtenerProducto($id);
    }
}

// Manejo de peticiones
$productoController = new ProductoController();

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];

    switch ($action) {
        case 'listar':
            echo json_encode(['success' => true, 'productos' => $productoController->listarProductos()]);
            break;
        case 'listar-creador':
            if (isset($_SESSION['usuario'])) {
                echo json_encode(['success' => true, 'productos' => $productoController->listarProductosPorCreador($_SESSION['usuario']['id'])]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
            }
            break;
        case 'crear':
            echo json_encode($productoController->crearProducto());
            break;
        case 'actualizar':
            echo json_encode($productoController->actualizarProducto());
            break;
        case 'eliminar':
            if (isset($_GET['id'])) {
                echo json_encode($productoController->eliminarProducto($_GET['id']));
            } else {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            }
            break;
        case 'obtener':
            if (isset($_GET['id'])) {
                $producto = $productoController->obtenerProducto($_GET['id']);
                if ($producto) {
                    echo json_encode(['success' => true, 'producto' => $producto]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            }
            break;
        case 'agregar':
            if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
                echo json_encode(['success' => false, 'message' => 'No tienes permisos para agregar productos']);
                exit();
            }

            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $precio = floatval($_POST['precio'] ?? 0);
            $stock = intval($_POST['stock'] ?? 0);
            $id_usuario = $_SESSION['usuario']['id'];

            // Validar datos
            if (empty($nombre) || empty($descripcion) || $precio <= 0 || $stock < 0) {
                echo json_encode(['success' => false, 'message' => 'Todos los campos son requeridos y deben ser válidos']);
                exit();
            }

            // Procesar imagen
            $imagen_url = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = '../../uploads/productos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $file_extension = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                if (!in_array($file_extension, $allowed_extensions)) {
                    echo json_encode(['success' => false, 'message' => 'Solo se permiten imágenes JPG, JPEG, PNG y GIF']);
                    exit();
                }

                $file_name = uniqid() . '.' . $file_extension;
                $upload_path = $upload_dir . $file_name;

                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $upload_path)) {
                    $imagen_url = 'uploads/productos/' . $file_name;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
                    exit();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'La imagen es requerida']);
                exit();
            }

            $producto = new Producto();
            $producto->setNombre($nombre);
            $producto->setDescripcion($descripcion);
            $producto->setPrecio($precio);
            $producto->setStock($stock);
            $producto->setImagenUrl($imagen_url);
            $producto->setIdUsuario($id_usuario);

            $crudProducto = new CrudProducto();
            if ($crudProducto->insertar($producto)) {
                echo json_encode(['success' => true, 'message' => 'Producto agregado exitosamente']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al agregar el producto']);
            }
            exit();
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
    }
}
?>