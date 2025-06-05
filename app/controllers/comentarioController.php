<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || !isset($_POST['comentario']) || !isset($_POST['id_video'])) {
    die(json_encode(['success' => false, 'message' => 'Acción no permitida']));
}

// Ajustamos el acceso al ID del usuario según `loginController.php`
$id_usuario = $_SESSION['usuario']['id'];  // Usamos 'id' en lugar de 'id_usuario'
$id_video = intval($_POST['id_video']);
$comentario = trim($_POST['comentario']);

if (empty($comentario)) {
    die(json_encode(['success' => false, 'message' => 'El comentario no puede estar vacío']));
}

$conn = Db::conectar();
$stmt = $conn->prepare("INSERT INTO comentarios_video (id_video, id_usuario, comentario, fecha_comentario, estado) VALUES (:id_video, :id_usuario, :comentario, NOW(), 'pendiente')");
$stmt->bindParam(':id_video', $id_video, PDO::PARAM_INT);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':comentario', $comentario, PDO::PARAM_STR);

if ($stmt->execute()) {
    header("Location: ../../app/views/comentarios/video_detalle.php?id_video=$id_video");
} else {
    die(json_encode(['success' => false, 'message' => 'Error al guardar el comentario']));
}
?>
