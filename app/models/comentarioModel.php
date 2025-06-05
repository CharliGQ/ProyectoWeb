<?php
require_once '../../config/database.php';

function crearComentario($id_video, $id_usuario, $comentario) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("INSERT INTO comentarios_video (id_video, id_usuario, comentario) VALUES (?, ?, ?)");
        return $stmt->execute([$id_video, $id_usuario, $comentario]);
    } catch (PDOException $e) {
        error_log("Error al crear comentario: " . $e->getMessage());
        return false;
    }
}

function listarComentariosPorVideo($id_video) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("
            SELECT c.*, u.nombre_usuario 
            FROM comentarios_video c
            JOIN usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.id_video = ? AND c.estado != 'eliminado'
            ORDER BY c.fecha_comentario DESC
        ");
        $stmt->execute([$id_video]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error al listar comentarios: " . $e->getMessage());
        return [];
    }
}

function reportarComentario($id_comentario, $id_reportante, $motivo) {
    try {
        $conn = Db::conectar();
        $stmt = $conn->prepare("INSERT INTO reportes_comentarios (id_comentario, id_reportante, motivo, estado) VALUES (?, ?, ?, 'pendiente')");
        return $stmt->execute([$id_comentario, $id_reportante, $motivo]);
    } catch (PDOException $e) {
        error_log("Error al reportar comentario: " . $e->getMessage());
        return false;
    }
}
?>