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

    // Obtener video por ID
function obtenerVideoPorId($id_video) {
    $db = Db::conectar();
    $stmt = $db->prepare("SELECT * FROM videos WHERE id_video = :id");
    $stmt->bindValue(':id', $id_video, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Actualizar título y descripción del video
function actualizarVideo($id_video, $titulo, $descripcion) {
    $db = Db::conectar();
    $stmt = $db->prepare("UPDATE videos SET titulo = :titulo, descripcion = :descripcion WHERE id_video = :id");
    $stmt->bindValue(':titulo', $titulo);
    $stmt->bindValue(':descripcion', $descripcion);
    $stmt->bindValue(':id', $id_video, PDO::PARAM_INT);
    return $stmt->execute();
}
}
?>
