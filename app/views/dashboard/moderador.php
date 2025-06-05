<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'moderador') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Moderador</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/theme-toggle.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <div class="nav-header">
                <h2>Panel de Moderador</h2>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            </div>
            <ul class="nav-menu">
                <li><a href="#" class="active">Inicio</a></li>
                <li><a href="#">Moderación</a></li>
                <li><a href="#">Reportes</a></li>
                <li><a href="#">Usuarios</a></li>
                <li><a href="#">Configuración</a></li>
                <li><a href="../../controllers/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1>Dashboard de Moderador</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['usuario']['correo']); ?></span>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <h3>Reportes Pendientes</h3>
                    <div class="reports-list">
                        <p>No hay reportes pendientes</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3>Actividad de Moderación</h3>
                    <div class="moderation-stats">
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Reportes Atendidos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Usuarios Sancionados</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Contenido Eliminado</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3>Acciones Rápidas</h3>
                    <div class="quick-actions">
                        <button class="btn-action">Revisar Reportes</button>
                        <button class="btn-action">Ver Usuarios Activos</button>
                        <button class="btn-action">Moderar Contenido</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include('../components/theme-toggle.php'); ?>
</body>
</html> 