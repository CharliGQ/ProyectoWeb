<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['usuario']) || !isset($_POST['id_comentario']) || !isset($_POST['tipo_voto'])) {
    die(json_encode(['success' => false, 'message' => 'Acción no permitida']));
}

$id_usuario = $_SESSION['usuario']['id'];
$id_comentario = intval($_POST['id_comentario']);
$tipo_voto = $_POST['tipo_voto'];

$conn = Db::conectar();

// Verificar si el usuario ya votó
$stmt = $conn->prepare("SELECT * FROM votos_usuario WHERE id_comentario = :id_comentario AND id_usuario = :id_usuario");
$stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->execute();
$votoExistente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($votoExistente) {
    die(json_encode(['success' => false, 'message' => 'Ya votaste este comentario']));
}

// Obtener el número actual de votos
$stmt = $conn->prepare("SELECT votos FROM comentarios_video WHERE id_comentario = :id_comentario");
$stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
$stmt->execute();
$comentario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comentario) {
    die(json_encode(['success' => false, 'message' => 'Comentario no encontrado']));
}

// Incrementar o decrementar votos
$nuevoVoto = $tipo_voto === 'up' ? $comentario['votos'] + 1 : $comentario['votos'] - 1;

// Guardar el voto en la tabla `votos_usuario`
$stmt = $conn->prepare("INSERT INTO votos_usuario (id_comentario, id_usuario, tipo_voto) VALUES (:id_comentario, :id_usuario, :tipo_voto)");
$stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
$stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmt->bindParam(':tipo_voto', $tipo_voto, PDO::PARAM_STR);
$stmt->execute();

// Actualizar la cantidad de votos en `comentarios_video`
$stmt = $conn->prepare("UPDATE comentarios_video SET votos = :votos WHERE id_comentario = :id_comentario");
$stmt->bindParam(':votos', $nuevoVoto, PDO::PARAM_INT);
$stmt->bindParam(':id_comentario', $id_comentario, PDO::PARAM_INT);
$stmt->execute();

echo json_encode(['success' => true, 'nuevoVoto' => $nuevoVoto]);
?>
