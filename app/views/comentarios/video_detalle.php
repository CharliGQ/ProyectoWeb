<?php
session_start();
require_once '../../../app/config/database.php';

// Validación de parámetros
if (!isset($_GET['id_video'])) {
    die("Video no especificado");
}
$id_video = intval($_GET['id_video']);

// Conexión a la base de datos
$conn = Db::conectar();

// Obtener detalles del video
$stmt = $conn->prepare(
    "SELECT titulo, descripcion, url_video 
     FROM videos 
     WHERE id_video = :id_video AND visibilidad = 'publico'"
);
$stmt->bindParam(':id_video', $id_video, PDO::PARAM_INT);
$stmt->execute();
$video = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$video) {
    die("Video no encontrado o privado.");
}

// Determinar ordenamiento de comentarios
$orden = $_GET['orden'] ?? 'recientes';
$ordenSQL = $orden === 'populares'
    ? "ORDER BY c.votos DESC, c.fecha_comentario DESC"
    : "ORDER BY c.fecha_comentario DESC";

// Obtener comentarios y respuestas
$stmtComentarios = $conn->prepare(
    "SELECT c.id_comentario, c.id_padre, c.comentario, c.fecha_comentario, c.votos, u.nombre_usuario 
     FROM comentarios_video c 
     JOIN usuarios u ON c.id_usuario = u.id_usuario 
     WHERE c.id_video = :id_video 
     $ordenSQL"
);
$stmtComentarios->bindParam(':id_video', $id_video, PDO::PARAM_INT);
$stmtComentarios->execute();
$comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

// Función para extraer ID de YouTube
function getYoutubeID($url) {
    $regExp = "/(?:youtube\\.com\\/.*(?:\\?|&)v=|youtu\\.be\\/)([\\w-]{11})/";
    preg_match($regExp, $url, $match);
    return $match[1] ?? false;
}
$videoId = getYoutubeID($video['url_video']);
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <div class="return-container">
    <a href="../dashboard/ver_videos.php" class="btn-return">⬅</a>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($video['titulo']) ?></title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/comentarios.css">
    <link rel="stylesheet" href="../../assets/css/ver_videos.css">
</head>
<body>
    <div class="video-container">
        <h1><?= htmlspecialchars($video['titulo']) ?></h1>
        <iframe width="100%" height="500px" src="https://www.youtube.com/embed/<?= $videoId ?>" allowfullscreen></iframe>
        <p><?= htmlspecialchars($video['descripcion']) ?></p>
    </div>

    <div class="sort-comments">
        <label for="ordenComentarios">Ordenar por:</label>
        <select id="ordenComentarios" onchange="cambiarOrden()">
            <option value="recientes" <?= $orden === 'recientes' ? 'selected' : '' ?>>Más recientes</option>
            <option value="populares" <?= $orden === 'populares' ? 'selected' : '' ?>>Más votados</option>
        </select>
    </div>

    <div class="comments-section">
        <h2>Comentarios</h2>
        <div class="comments-list">
            <?php
            
            // Organizar comentarios por id_padre
            $comentariosPrincipales = [];
            $respuestas = [];
            foreach ($comentarios as $comentario) {
                if (!$comentario['id_padre']) {
                    $comentariosPrincipales[$comentario['id_comentario']] = $comentario;
                } else {
                    $respuestas[$comentario['id_padre']][] = $comentario;
                }
            }

            // Función para mostrar respuestas
            function mostrarRespuestas($idPadre, $respuestas) {
                if (!isset($respuestas[$idPadre])) return;
                foreach ($respuestas[$idPadre] as $respuesta): ?>
                    <div class="reply">
                        <p><strong><?= htmlspecialchars($respuesta['nombre_usuario']) ?>:</strong></p>
                        <p><?= htmlspecialchars($respuesta['comentario']) ?></p>
                        <span class="comment-date"><?= htmlspecialchars($respuesta['fecha_comentario']) ?></span>
                    </div>
                <?php endforeach;
            }
            ?>


            <?php foreach ($comentariosPrincipales as $comentario): ?>
                <div class="comment">
                    <p><strong><?= htmlspecialchars($comentario['nombre_usuario']) ?>:</strong></p>
                    <p><?= htmlspecialchars($comentario['comentario']) ?></p>
                    <span class="comment-date"><?= htmlspecialchars($comentario['fecha_comentario']) ?></span>
                    
                    <div class="comment-actions">
                        <div class="vote-buttons">
                            <button class="vote-btn" onclick="votar(<?= $comentario['id_comentario'] ?>, 'up')">👍</button>
                            <button class="vote-btn" onclick="votar(<?= $comentario['id_comentario'] ?>, 'down')">👎</button>
                            <span class="vote-count" id="votos-<?= $comentario['id_comentario'] ?>"><?= $comentario['votos'] ?></span>
                        </div>
                        <?php if (isset($_SESSION['usuario'])): ?>
                        <!-- Mostrar respuestas -->
                        <div class="comment-replies" id="replies-<?= $comentario['id_comentario'] ?>">
                            <?php foreach ($comentarios as $respuesta): ?>
                                <?php if ($respuesta['id_padre'] == $comentario['id_comentario']): ?>
                                    <div class="reply">
                                        <p><strong><?= htmlspecialchars($respuesta['nombre_usuario']) ?>:</strong></p>
                                        <p><?= htmlspecialchars($respuesta['comentario']) ?></p>
                                        <span class="comment-date"><?= htmlspecialchars($respuesta['fecha_comentario']) ?></span>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <!-- Formulario para responder -->
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <button onclick="mostrarFormulario(<?= $comentario['id_comentario'] ?>)">Responder</button>
                            <form action="../../controllers/respuestaController.php" method="POST" class="reply-form" id="form-<?= $comentario['id_comentario'] ?>" style="display: none;">
                                <textarea name="comentario" placeholder="Escribe tu respuesta..." required></textarea>
                                <input type="hidden" name="id_video" value="<?= $id_video ?>">
                                <input type="hidden" name="id_padre" value="<?= $comentario['id_comentario'] ?>">
                                <button type="submit">Enviar respuesta</button>
                            </form>
                            <!-- Botón de reporte -->
                    <?php if (isset($_SESSION['usuario'])): ?>
                        <button class="btn-report" onclick="abrirReporte(<?= $comentario['id_comentario'] ?>)">⚠️ Reportar</button>
                    <?php endif; ?>
                </div>

                <!-- Modal de reporte -->
                <div class="report-modal" id="modal-reporte-<?= $comentario['id_comentario'] ?>">
                    <form action="../../controllers/reportesController.php" method="POST">
                        <h3>Reportar Comentario</h3>
                        <select name="tipo_reporte" required>
    <option value="spam">Spam</option>
    <option value="abuso">Abuso</option>
    <option value="lenguaje inapropiado">Lenguaje inapropiado</option>
    <option value="otro" selected>Otro</option>
</select>

                        <p>Motivo del reporte:</p>
                        <textarea name="motivo" required></textarea>
                        <input type="hidden" name="id_comentario" value="<?= $comentario['id_comentario'] ?>">
                        <input type="hidden" name="id_video" value="<?= intval($_GET['id_video']) ?>">
                        <button type="submit">Enviar Reporte</button>
                        <button type="button" onclick="cerrarReporte(<?= $comentario['id_comentario'] ?>)">Cancelar</button>
                    </form>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

        

        <?php if (isset($_SESSION['usuario'])): ?>
            <form action="../../../app/controllers/comentarioController.php" method="POST" class="comment-form">
                <textarea name="comentario" placeholder="Escribe un comentario..." required></textarea>
                <input type="hidden" name="id_video" value="<?= $id_video ?>">
                <button type="submit">Enviar comentario</button>
            </form>
        <?php else: ?>
            <p>Inicia sesión para comentar.</p>
        <?php endif; ?>
    </div>

    <script src="../../assets/js/votos.js"></script>
    <script>
        function cambiarOrden() {
            const orden = document.getElementById('ordenComentarios').value;
            const params = new URLSearchParams(window.location.search);
            params.set('orden', orden);
            window.location.search = params.toString();
        }

        function mostrarFormulario(idComentario) {
            document.getElementById(`form-${idComentario}`).style.display = 'block';
        }

        document.getElementById('ordenComentarios').value = "<?= $orden ?>";
    </script>
</body>
</html>
