<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'registrado') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<div class="return-container">
    <a href="../dashboard/registrado.php" class="btn-return">⬅ Volver al Inicio</a>
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