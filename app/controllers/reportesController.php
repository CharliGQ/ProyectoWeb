<?php
session_start();

require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || !isset($_POST['motivo']) || !isset($_POST['tipo_reporte']) || !isset($_POST['id_comentario']) || !isset($_POST['id_video'])) {
    die(json_encode(['success' => false, 'message' => 'Acción no permitida']));
}

$id_reportante = $_SESSION['usuario']['id'];
$id_comentario = intval($_POST['id_comentario']);
$id_video = intval($_POST['id_video']); // Nuevo campo
$motivo = trim($_POST['motivo']);
$tipo_reporte = trim($_POST['tipo_reporte']);

if (empty($motivo)) {
    die(json_encode(['success' => false, 'message' => 'El motivo no puede estar vacío']));
}

$conn = Db::conectar();
$stmt = $conn->prepare("INSERT INTO reportes_comentarios (id_comentario, id_video, id_reportante, motivo, tipo_reporte, fecha_reporte, estado) VALUES (:id_comentario, :id_video, :id_reportante, :motivo, :tipo_reporte, NOW(), 'pendiente')");
$stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
$stmt->bindParam(':id_video', $id_video, PDO::PARAM_INT);
$stmt->bindParam(':id_reportante', $id_reportante, PDO::PARAM_INT);
$stmt->bindParam(':motivo', $motivo, PDO::PARAM_STR);
$stmt->bindParam(':tipo_reporte', $tipo_reporte, PDO::PARAM_STR);

if ($stmt->execute()) {
    header("Location: ../views/comentarios/video_detalle.php?id_video=$id_video");
    exit();
} else {
    die(json_encode(['success' => false, 'message' => 'Error al guardar el reporte']));
}
?>
