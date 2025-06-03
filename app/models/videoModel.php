<?php
require_once '../config/database.php';

function crearVideo($titulo, $descripcion, $url_video, $id_usuario) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("INSERT INTO videos (titulo, descripcion, url_video, id_usuario, plataforma, estado, visibilidad) VALUES (?, ?, ?, ?, 'YouTube', 'activo', 'publico')");
        return $stmt->execute([$titulo, $descripcion, $url_video, $id_usuario]);
    } catch (PDOException $e) {
        error_log("Error al insertar vídeo: " . $e->getMessage());
        return false;
    }
}

function eliminarVideo($id_video) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("UPDATE videos SET estado = 'eliminado' WHERE id_video = ?");
        return $stmt->execute([$id_video]);
    } catch (PDOException $e) {
        error_log("Error al eliminar vídeo: " . $e->getMessage());
        return false;
    }
}

function listarVideosPorCreador($id_usuario) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("SELECT * FROM videos WHERE id_usuario = ? AND estado != 'eliminado'");
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al listar vídeos: " . $e->getMessage());
        return [];
    }
}
?>
