<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'main_owner') {
    header('Location: ../login.php');
    exit();
}

// Cargar datos del sistema
require_once '../../models/adminModel.php';
$adminModel = new AdminModel();

$totalUsuarios = $adminModel->getTotalUsuarios();
$totalModeradores = $adminModel->getTotalModeradores();
$totalReportesActivos = $adminModel->getTotalReportesActivos();
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
        <!-- Sidebar de navegación -->
        <nav class="dashboard-nav">
            <div class="nav-header">
                <h2>Panel de Administrador</h2>
                <p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?></p>
            </div>
            <ul class="nav-menu">
                <li><a href="#" class="active">Inicio</a></li>
                <li><a href="#">Gestión de Usuarios</a></li>
                <li><a href="#">Gestión de Moderadores</a></li>
                <li><a href="#">Configuración del Sistema</a></li>
                <li><a href="#">Logs del Sistema</a></li>
                <li><a href="#">Backup</a></li>
                <li><a href="../controllers/loginController.php?action=logout">Cerrar Sesión</a></li>
            </ul>
        </nav>

        <!-- Contenido principal -->
        <main class="dashboard-main">
            <!-- Encabezado -->
            <div class="dashboard-header">
                <h1>Dashboard de Administrador</h1>
                <div class="user-info">
                    <span><?= htmlspecialchars($_SESSION['usuario']['correo']) ?></span>
                </div>
            </div>

            <!-- Mensajes de éxito o error -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert success"><?= $_SESSION['success'] ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert error"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Secciones principales -->
            <section class="dashboard-grid">

                <!-- Resumen del Sistema -->
                <div class="dashboard-card">
                    <h3>Resumen del Sistema</h3>
                    <div class="system-stats">
                        <div class="stat-item">
                            <span class="stat-value"><?= $totalUsuarios ?></span>
                            <span class="stat-label">Usuarios Totales</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $totalModeradores ?></span>
                            <span class="stat-label">Moderadores</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value"><?= $totalReportesActivos ?></span>
                            <span class="stat-label">Reportes Activos</span>
                        </div>
                    </div>
                </div>

                <!-- Acciones de administración -->
                <div class="dashboard-card card-actions">
                    <h3>Acciones de Administración</h3>
                    <div class="admin-actions">
                        <a href="?seccion=usuarios" class="btn-action">Gestionar Usuarios</a>
                        <a href="?seccion=moderadores" class="btn-action">Asignar Moderadores</a>
                        <a href="?seccion=configuracion" class="btn-action">Configuración del Sistema</a>
                        <a href="?seccion=backup" class="btn-action">Realizar Backup</a>
                    </div>
                </div>

                <!-- Contenido dinámico -->
                <?php if (isset($_GET['seccion'])): ?>
                    <div class="dashboard-card dynamic-content">
                        <?php 
                            switch ($_GET['seccion']) {
                                case 'usuarios':
                                    include '../admin/usuarios.php';
                                    break;
                                case 'moderadores':
                                    include '../admin/moderadores.php';
                                    break;
                                case 'configuracion':
                                    include '../admin/configuracion.php';
                                    break;
                                case 'backup':
                                    include '../admin/backup.php';
                                    break;
                                default:
                                    echo "<p>Sección no encontrada.</p>";
                            }
                        ?>
                    </div>
                <?php endif; ?>

                <!-- Estado del sistema -->
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

            </section>
        </main>
    </div>

    <!-- Componente de tema oscuro/claro -->
    <?php include('../components/theme-toggle.php'); ?>
</body>
</html>