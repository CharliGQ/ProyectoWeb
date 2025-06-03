<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Verificar si el usuario está autenticado como creador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
    header('Location: ../login.php');
    exit();
}

require_once '../models/videoModel.php';

// Obtener acción desde POST o GET
$accion = $_GET['action'] ?? ($_POST['action'] ?? '');

switch ($accion) {
    case 'subir':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo = trim($_POST['titulo'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $url_video = trim($_POST['url_video'] ?? '');
            $id_usuario = $_SESSION['usuario']['id'];

            // Validaciones básicas
            if (empty($titulo)) {
                echo json_encode(['success' => false, 'message' => 'El título del video es obligatorio.']);
                exit();
            }

            // Si hay archivo subido
            if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
                $nombreArchivo = basename($_FILES['video']['name']);
                $tmpNombre = $_FILES['video']['tmp_name'];
                $carpetaDestino = '../uploads/videos/';
                $rutaAlmacenada = $carpetaDestino . $nombreArchivo;

                // Crear carpeta si no existe
                if (!is_dir($carpetaDestino)) {
                    mkdir($carpetaDestino, 0777, true);
                }

                // Mover archivo
                if (move_uploaded_file($tmpNombre, $rutaAlmacenada)) {
                    $url_video = $rutaAlmacenada;
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar el archivo de video.']);
                    exit();
                }
            } elseif (empty($url_video)) {
                echo json_encode(['success' => false, 'message' => 'Debe proporcionar una URL de video o subir un archivo.']);
                exit();
            }

            // Intentar guardar en la BD
            if (crearVideo($titulo, $descripcion, $url_video, $id_usuario)) {
                echo json_encode(['success' => true, 'message' => 'Vídeo creado correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error al guardar el vídeo en la base de datos.']);
            }
        }
        break;

    case 'eliminar':
        if (isset($_GET['id'])) {
            $id_video = intval($_GET['id']);
            if (eliminarVideo($id_video)) {
                header('Location: ../views/dashboard/creador.php');
                exit();
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el video.']);
            }
        }
        break;

    case 'listar':
        $id_usuario = $_SESSION['usuario']['id'];
        $videos = listarVideosPorCreador($id_usuario);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Lista de videos cargada.', 'videos' => $videos]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        break;
}
?>
