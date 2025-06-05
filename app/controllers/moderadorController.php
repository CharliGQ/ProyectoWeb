<?php
session_start();
require_once '../config/database.php';

$conn = Db::conectar();

// Validar si se recibió un ID de usuario para sancionar
if (isset($_POST['id_usuario']) && $_POST['accion'] === 'sancionar') {
    $id_usuario = intval($_POST['id_usuario']);

    $stmtSancionar = $conn->prepare("UPDATE usuarios SET sancionado = 1 WHERE id_usuario = :id_usuario");
    $stmtSancionar->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);

    if ($stmtSancionar->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario sancionado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al sancionar usuario']);
    }
    exit();
}

// Validar si se recibió un ID de reporte para procesar
if (isset($_POST['id_reporte']) && isset($_POST['estado'])) {
    $id_reporte = intval($_POST['id_reporte']);
    $estado = $_POST['estado'];

    // Si el reporte es descartado, eliminar el comentario asociado
    if ($estado === 'descartado') {
        try {
            $conn->beginTransaction();

            $stmtEliminar = $conn->prepare("DELETE FROM comentarios_video WHERE id_comentario = (SELECT id_comentario FROM reportes_comentarios WHERE id_reporte = :id_reporte)");
            $stmtEliminar->bindParam(':id_reporte', $id_reporte, PDO::PARAM_INT);
            $stmtEliminar->execute();

            // Después de eliminar el comentario, eliminar también el reporte
            $stmtEliminarReporte = $conn->prepare("DELETE FROM reportes_comentarios WHERE id_reporte = :id_reporte");
            $stmtEliminarReporte->bindParam(':id_reporte', $id_reporte, PDO::PARAM_INT);
            $stmtEliminarReporte->execute();

            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Comentario eliminado']);
        } catch (Exception $e) {
            $conn->rollBack();
            echo json_encode(['success' => false, 'message' => 'Error al eliminar comentario: ' . $e->getMessage()]);
        }
        exit();
    }

    // Si el reporte no es descartado, actualizar su estado
    $stmt = $conn->prepare("UPDATE reportes_comentarios SET estado = :estado WHERE id_reporte = :id_reporte");
    $stmt->bindParam(':estado', $estado, PDO::PARAM_STR);
    $stmt->bindParam(':id_reporte', $id_reporte, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Reporte actualizado']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar reporte']);
    }
    exit();
}

// Si ninguna acción es válida, devolver un error
echo json_encode(['success' => false, 'message' => 'Acción no válida']);
exit();
?>
