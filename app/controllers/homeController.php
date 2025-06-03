<?php
require_once '../config/database.php';

class HomeController {
    private $conn;

    public function __construct() {
        $this->conn = Db::conectar();
    }

    // Obtener últimos vídeos públicos
    public function getUltimosVideos() {
        $stmt = $this->conn->prepare("SELECT id_video, titulo, url_video FROM videos WHERE visibilidad = 'publico' AND estado = 'activo' ORDER BY fecha_publicacion DESC LIMIT 6");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener últimos productos
    public function getUltimosProductos() {
        $stmt = $this->conn->prepare("SELECT id_producto, nombre, descripcion, imagen_url, precio FROM productos WHERE stock > 0 ORDER BY fecha_creacion DESC LIMIT 6");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$home = new HomeController();

if (isset($_GET['action'])) {
    header('Content-Type: application/json');
    $action = $_GET['action'];

    switch ($action) {
        case 'videos':
            echo json_encode(['success' => true, 'videos' => $home->getUltimosVideos()]);
            break;
        case 'productos':
            echo json_encode(['success' => true, 'productos' => $home->getUltimosProductos()]);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no reconocida']);
    }
}
?>