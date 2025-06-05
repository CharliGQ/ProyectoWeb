<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'registrado') {
    header('Location: ../login.php');
    exit();
}
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
                <li><a href="#">Videos</a></li>
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
                            <span class="stat-value">0</span>
                            <span class="stat-label">Publicaciones</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Comentarios</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Likes</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include('../components/theme-toggle.php'); ?>
</body>
</html> 