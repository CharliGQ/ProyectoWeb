<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'registrado') {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

$conn = Db::conectar();
$id_usuario = $_SESSION['usuario']['id'];

// Obtener cantidad de comentarios del usuario
$stmtComentarios = $conn->prepare("SELECT COUNT(*) AS total_comentarios FROM comentarios_video WHERE id_usuario = :id_usuario");
$stmtComentarios->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmtComentarios->execute();
$totalComentarios = $stmtComentarios->fetch(PDO::FETCH_ASSOC)['total_comentarios'];

// Obtener cantidad total de likes recibidos en sus comentarios
$stmtLikes = $conn->prepare("SELECT SUM(votos) AS total_likes FROM comentarios_video WHERE id_usuario = :id_usuario");
$stmtLikes->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
$stmtLikes->execute();
$totalLikes = $stmtLikes->fetch(PDO::FETCH_ASSOC)['total_likes'] ?: 0; // Si no hay likes, mostrar 0
?>

<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Usuario Registrado</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <div class="nav-header">
                <h2>Panel de Usuario</h2>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            </div>
            <ul class="nav-menu">
                <li><a href="registrado.php" class="active">Inicio</a></li>
                <li><a href="../perfil.php">Mi Perfil</a></li>
                <li><a href="ver_videos.php">Videos</a></li>
                <li><a href="../productos.php">Productos</a></li>
                <li><a href="../mis-pedidos.php">Mis Pedidos</a></li>
                <li><a href="../../controllers/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1>Dashboard de Usuario</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['usuario']['correo']); ?></span>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <h3>Actividad Reciente</h3>
                    <div class="activity-list">
                        <p>No hay actividad reciente</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
    <h3>Estadísticas</h3>
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($totalComentarios) ?></span>
            <span class="stat-label">Comentarios</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($totalLikes) ?></span>
            <span class="stat-label">Likes Recibidos</span>
        </div>
    </div>
</div>

            </div>
        </main>
    </div>
    <?php include('../components/theme-toggle.php'); ?>
</body>
</html> 