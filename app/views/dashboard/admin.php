<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'main_owner') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Administrador</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <div class="nav-header">
                <h2>Panel de Administrador</h2>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            </div>
            <ul class="nav-menu">
                <li><a href="#" class="active">Inicio</a></li>
                <li><a href="#">Gestión de Usuarios</a></li>
                <li><a href="#">Gestión de Moderadores</a></li>
                <li><a href="#">Configuración del Sistema</a></li>
                <li><a href="#">Logs del Sistema</a></li>
                <li><a href="#">Backup</a></li>
                <li><a href="../../controllers/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>
        
        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1>Dashboard de Administrador</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['usuario']['correo']); ?></span>
                </div>
            </div>
            
            <div class="dashboard-content">
                <div class="dashboard-card">
                    <h3>Resumen del Sistema</h3>
                    <div class="system-stats">
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Usuarios Totales</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Moderadores</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Reportes Activos</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3>Acciones de Administración</h3>
                    <div class="admin-actions">
                        <button class="btn-action">Gestionar Usuarios</button>
                        <button class="btn-action">Asignar Moderadores</button>
                        <button class="btn-action">Configuración del Sistema</button>
                        <button class="btn-action">Realizar Backup</button>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3>Logs del Sistema</h3>
                    <div class="system-logs">
                        <p>No hay logs recientes</p>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <h3>Estado del Sistema</h3>
                    <div class="system-status">
                        <div class="status-item">
                            <span class="status-label">Base de Datos:</span>
                            <span class="status-value online">Online</span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Servidor:</span>
                            <span class="status-value online">Online</span>
                        </div>
                        <div class="status-item">
                            <span class="status-label">Último Backup:</span>
                            <span class="status-value">Nunca</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php include('../components/theme-toggle.php'); ?>
</body>
</html> 