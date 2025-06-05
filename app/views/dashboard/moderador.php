<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'moderador') {
    header('Location: ../login.php');
    exit();
}

require_once '../../config/database.php';

$conn = Db::conectar();

$limit = 5; // Número de reportes por página
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

$stmtReportes = $conn->prepare(
    "SELECT r.id_reporte, r.id_comentario, r.id_reportante, r.motivo, r.fecha_reporte, r.estado, r.tipo_reporte, r.id_video, 
            u.nombre_usuario AS reportante, c.comentario
     FROM reportes_comentarios r
     JOIN usuarios u ON r.id_reportante = u.id_usuario
     JOIN comentarios_video c ON r.id_comentario = c.id_comentario
     WHERE r.estado = 'pendiente'
     ORDER BY r.fecha_reporte DESC
     LIMIT :limit OFFSET :offset"
);
$stmtReportes->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmtReportes->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmtReportes->execute();
$reportes = $stmtReportes->fetchAll(PDO::FETCH_ASSOC);

// Obtener cantidad de reportes atendidos
$stmtAtendidos = $conn->prepare("SELECT COUNT(*) AS total_atendidos FROM reportes_comentarios WHERE estado = 'revisado'");
$stmtAtendidos->execute();
$totalAtendidos = $stmtAtendidos->fetch(PDO::FETCH_ASSOC)['total_atendidos'];

// Obtener cantidad de usuarios sancionados (si hay sanción por reporte)
$stmtSancionados = $conn->prepare("SELECT COUNT(*) AS total_sancionados FROM usuarios WHERE sancionado = 1");
$stmtSancionados->execute();
$totalSancionados = $stmtSancionados->fetch(PDO::FETCH_ASSOC)['total_sancionados'];

// Obtener cantidad de comentarios eliminados
$stmtEliminados = $conn->prepare("SELECT COUNT(*) AS total_eliminados FROM reportes_comentarios WHERE estado = 'descartado'");
$stmtEliminados->execute();
$totalEliminados = $stmtEliminados->fetch(PDO::FETCH_ASSOC)['total_eliminados'];

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
<<<<<<< HEAD
                <li><a href="../../controllers/logout.php">Cerrar Sesión</a></li>
=======
                <li><a href="../../controllers/loginController.php?action=logout">Cerrar Sesión</a></li>
>>>>>>> 1f82ad15c4f3f2537beb59d4d3e72def12bc228e
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
    <!-- Sección de Reportes Pendientes -->
    <div class="dashboard-card full-width">
        <h3>Reportes Pendientes</h3>
        <div class="reports-list">
            <?php if (count($reportes) > 0): ?>
                <table class="reports-table">
                    <thead>
                        <tr>
                            <th>Reportante</th>
                            <th>Comentario</th>
                            <th>Motivo</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reportes as $reporte): ?>
                            <tr>
                                <td><?= htmlspecialchars($reporte['reportante']) ?></td>
                                <td><?= htmlspecialchars($reporte['comentario']) ?></td>
                                <td><?= htmlspecialchars($reporte['motivo']) ?></td>
                                <td><?= htmlspecialchars($reporte['tipo_reporte']) ?></td>
                                <td><?= htmlspecialchars($reporte['fecha_reporte']) ?></td>
                                <td><?= htmlspecialchars($reporte['estado']) ?></td>
                                <td>
                                    <button class="btn-action" onclick="resolverReporte(<?= $reporte['id_reporte'] ?>, 'revisado')">✅ Revisado</button>
                                    <button class="btn-action btn-danger" onclick="resolverReporte(<?= $reporte['id_reporte'] ?>, 'descartado')">❌ Eliminar</button>
                                </td>
                                <td>
    <button class="btn-action btn-warning" onclick="sancionarUsuario(<?= $reporte['id_reportante'] ?>)">🚫 Sancionar Usuario</button>
</td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No hay reportes pendientes.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sección de Actividad de Moderación -->
    <div class="dashboard-card full-width">
    <h3>Actividad de Moderación</h3>
    <div class="moderation-stats">
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($totalAtendidos) ?></span>
            <span class="stat-label">Reportes Atendidos</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($totalSancionados) ?></span>
            <span class="stat-label">Usuarios Sancionados</span>
        </div>
        <div class="stat-item">
            <span class="stat-value"><?= htmlspecialchars($totalEliminados) ?></span>
            <span class="stat-label">Comentarios Eliminados</span>
        </div>
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
    <script src="../../assets/js/dashboard.js"></script>
</body>
</html> 
