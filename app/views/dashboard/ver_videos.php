<?php
session_start();
if (!isset($_SESSION['usuario']) || !in_array($_SESSION['usuario']['rol'], ['registrado', 'creador', 'moderador'])) {
    header('Location: login.php');
    exit();
}

require_once '../../config/database.php';
$conn = Db::conectar();
$id_usuario = $_SESSION['usuario']['id'];

if ($_SESSION['usuario']['rol'] === 'creador') {
    // Obtener solo los videos del creador
    $stmt = $conn->prepare("SELECT id_video, url_video FROM videos WHERE id_usuario = :id_usuario");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
} else {
    // Obtener solo los videos públicos
    $stmt = $conn->prepare("SELECT id_video, url_video FROM videos WHERE visibilidad = 'publico'");
}
$stmt->execute();
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<div class="return-container">
    <?php if ($_SESSION['usuario']['rol'] === 'creador'): ?>
        <a href="../dashboard/creador.php" class="btn-return">⬅ Volver al Panel de Creador</a>
    <?php elseif ($_SESSION['usuario']['rol'] === 'moderador'): ?>
        <a href="../dashboard/moderador.php" class="btn-return">⬅ Volver al Panel de Moderador</a>
    <?php else: ?>
        <a href="../dashboard/registrado.php" class="btn-return">⬅ Volver al Inicio</a>
    <?php endif; ?>
</div>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Mis Videos</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/ver_videos.css">
</head>
<body>
<div class="video-page-container">
    <h2>Videos Disponibles</h2>
    
    <div id="lista-videos" class="video-grid">
    <!-- Aquí se cargarán los vídeos dinámicamente -->
</div>

<script>
    // Cargar vídeos públicos desde homeController.php
    async function cargarVideos() {
        const contenedor = document.getElementById('lista-videos');

        try {
            const res = await fetch('../../../app/controllers/homeController.php?action=videos');
            const data = await res.json();

            if (data.success && data.videos.length > 0) {
                data.videos.forEach(videos => {
                    const videoId = getYoutubeID(videos.url_video);

                    if (!videoId) return;

                    contenedor.insertAdjacentHTML('beforeend', `
                        <div class="video-item">
                            <iframe src="https://www.youtube.com/embed/${videoId}"  allowfullscreen></iframe>
                            <a href="../comentarios/video_detalle.php?id_video=${videos.id_video}" class="btn-comentar-video">💬 Ver detalles y comentarios</a>
                        </div>
                    `);
                });
            } else {
                contenedor.innerHTML = '<p>No hay vídeos disponibles.</p>';
            }
        } catch (err) {
            console.error("Error al cargar vídeos:", err);
            contenedor.innerHTML = '<p>Error al cargar los vídeos.</p>';
        }
    }

    // Función para extraer ID de YouTube
    function getYoutubeID(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*$/;
        const match = url.match(regExp);
        return (match && match[7].length == 11) ? match[7] : false;
    }

    window.addEventListener('DOMContentLoaded', () => {
        cargarVideos();
    });
</script>
</div>

</body>
</html>