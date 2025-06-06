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

/*Función para obtener un video por ID*/
function obtenerVideoPorId($id_video) {
    $db = Db::conectar();
    $stmt = $db->prepare("SELECT * FROM videos WHERE id_video = :id");
    $stmt->bindValue(':id', $id_video, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/* Función para actualizar título y descripción del video*/
function actualizarVideo($id_video, $titulo, $descripcion) {
    $db = Db::conectar();
    $stmt = $db->prepare("UPDATE videos SET titulo = :titulo, descripcion = :descripcion WHERE id_video = :id");
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':id', $id_video, PDO::PARAM_INT);
    return $stmt->execute();
}

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

        case 'obtener':
        if (isset($_GET['id'])) {
            $id_video = intval($_GET['id']);
            $video = obtenerVideoPorId($id_video);
            if ($video && $video['id_usuario'] == $_SESSION['usuario']['id']) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'video' => $video]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Video no encontrado o sin permisos.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'ID de video no proporcionado.']);
        }
        break;

    case 'actualizar_video':
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id_video = intval($_POST['id_video'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (!$id_video || !$titulo) {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
            exit();
        }

        if (actualizarVideo($id_video, $titulo, $descripcion)) {
            echo json_encode(['success' => true, 'message' => '✅ Video actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => '❌ Error al actualizar el video.']);
        }
    }
    break;


    default:
        echo json_encode(['success' => false, 'message' => 'Acción no reconocida.']);
        break;

        
}

?>
