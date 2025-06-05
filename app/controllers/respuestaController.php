<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || !isset($_POST['comentario']) || !isset($_POST['id_video']) || !isset($_POST['id_padre'])) {
    die(json_encode(['success' => false, 'message' => 'Acción no permitida']));
}

$id_usuario = $_SESSION['usuario']['id'];
$id_video = intval($_POST['id_video']);
$id_padre = intval($_POST['id_padre']);
$comentario = trim($_POST['comentario']);

if (empty($comentario)) {
    die(json_encode(['success' => false, 'message' => 'La respuesta no puede estar vacía']));
}

$conn = Db::conectar();
$stmt = $conn->prepare("INSERT INTO comentarios_video (id_video, id_usuario, comentario, fecha_comentario, estado, id_padre) VALUES (:id_video, :id_usuario, :comentario, NOW(), 'pendiente', :id_padre)");
$stmt->bindParam(':id_video', $id_video, PDO::PARAM_INT);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);
$stmt->bindParam(':id_padre', $id_padre, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: ../../../comentarios/video_detalle.php?id_video=$id_video");
} else {
    die(json_encode(['success' => false, 'message' => 'Error al guardar la respuesta']));
}
?>
