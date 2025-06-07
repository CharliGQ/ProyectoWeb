<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Creador de Contenido</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/theme-toggle.css">
    <style>
        .dashboard-content {
            display: flex;
            flex-direction: column;
            gap: 2rem;
            padding: 1rem;
        }

        .dashboard-card {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h3 {
            color: var(--text-color);
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--primary-color);
        }

        /* Estilos para el resumen */
        .system-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stat-value {
            display: block;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.8rem;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Estilos para el formulario */
        .dashboard-card form {
            display: grid;
            gap: 1.8rem;
            max-width: 800px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.02);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .dashboard-card label {
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 0.8rem;
            display: block;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .dashboard-card input[type="text"],
        .dashboard-card input[type="number"],
        .dashboard-card textarea {
            width: 100%;
            padding: 1rem;
            border-radius: 8px;
            border: 2px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-color);
            font-size: 1rem;
        }

        [data-theme="dark"] .dashboard-card input[type="text"],
        [data-theme="dark"] .dashboard-card input[type="number"],
        [data-theme="dark"] .dashboard-card textarea {
            border-color: rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .dashboard-card input[type="text"]:focus,
        .dashboard-card input[type="number"]:focus,
        .dashboard-card textarea:focus {
            border-color: var(--primary-color);
            outline: none;
            background: rgba(255, 255, 255, 0.95);
        }

        [data-theme="dark"] .dashboard-card input[type="text"]:focus,
        [data-theme="dark"] .dashboard-card input[type="number"]:focus,
        [data-theme="dark"] .dashboard-card textarea:focus {
            background: rgba(255, 255, 255, 0.08);
        }

        .dashboard-card textarea {
            min-height: 120px;
            resize: vertical;
        }

        .dashboard-card input[type="file"] {
            padding: 1rem;
            border-radius: 8px;
            border: 2px dashed rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.8);
            color: var(--text-color);
            cursor: pointer;
            width: 100%;
        }

        [data-theme="dark"] .dashboard-card input[type="file"] {
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.05);
        }

        .dashboard-card input[type="file"]:hover {
            border-color: var(--primary-color);
        }

        .dashboard-card button[type="submit"] {
            background: var(--primary-color);
            color: white;
            padding: 1.2rem 2.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.1rem;
        }

        /* Estilos para la lista de productos */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 1rem;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .product-header img {
            border-radius: 10px;
            margin-bottom: 1rem;
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-header h4 {
            margin: 0;
            color: var(--text-color);
            font-size: 1.4rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .product-details {
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-details p {
            margin: 1rem 0;
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.6;
        }

        .product-details strong {
            color: var(--text-color);
            font-weight: 600;
            display: inline-block;
            margin-right: 0.5rem;
        }

        .product-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-edit, .btn-delete, .btn-delete-video {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-edit {
            background: var(--primary-color);
            color: white;
        }

        .btn-delete, .btn-delete-video {
            background: var(--danger-color);
            color: white;
        }

        /* Ajustes para el texto dentro de los inputs */
        .dashboard-card input[type="text"]::placeholder,
        .dashboard-card input[type="number"]::placeholder,
        .dashboard-card textarea::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        [data-theme="dark"] .dashboard-card input[type="text"]::placeholder,
        [data-theme="dark"] .dashboard-card input[type="number"]::placeholder,
        [data-theme="dark"] .dashboard-card textarea::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Ajustes para el color del texto en los inputs */
        .dashboard-card input[type="text"],
        .dashboard-card input[type="number"],
        .dashboard-card textarea {
            color: #333;
        }

        [data-theme="dark"] .dashboard-card input[type="text"],
        [data-theme="dark"] .dashboard-card input[type="number"],
        [data-theme="dark"] .dashboard-card textarea {
            color: #fff;
        }

        /* Estilos para la sección de pedidos */
        .pedidos-container {
            margin-top: 1.5rem;
        }

        .pedidos-filtros {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .filtro-select, .filtro-fecha {
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-color);
            font-size: 1rem;
        }

        .pedidos-tabla {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            overflow: hidden;
        }

        .pedidos-tabla th,
        .pedidos-tabla td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .pedidos-tabla th {
            background: rgba(255, 255, 255, 0.05);
            font-weight: 600;
            color: var(--text-color);
        }

        .pedidos-tabla tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }

        .estado-pedido {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            text-align: center;
        }

        .estado-pendiente { background: #ffd700; color: #000; }
        .estado-enviado { background: #1e90ff; color: #fff; }
        .estado-entregado { background: #32cd32; color: #fff; }
        .estado-cancelado { background: #ff4444; color: #fff; }

        .btn-accion-pedido {
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }

        .btn-ver-detalles {
            background: var(--primary-color);
            color: white;
        }

        .btn-actualizar-estado {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <nav class="dashboard-nav">
            <div class="nav-header">
                <h2>Panel del Creador</h2>
                <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
            </div>
            <ul class="nav-menu">
                <li><a href="#" class="active">Inicio</a></li>
                <li><a href="#videos">Mis Videos</a></li>
                <li><a href="#subir-video">Subir Video</a></li>
                <li><a href="#productos">Mis Productos</a></li>
                <li><a href="#agregar-producto">Agregar Producto</a></li>
                <li><a href="#pedidos">Pedidos</a></li>
                <li><a href="../../controllers/logout.php">Cerrar Sesión</a></li>
            </ul>
        </nav>

        <main class="dashboard-main">
            <div class="dashboard-header">
                <h1>Dashboard del Creador</h1>
                <div class="user-info">
                    <span><?php echo htmlspecialchars($_SESSION['usuario']['correo']); ?></span>
                </div>
            </div>

            <div class="dashboard-content">

                <!-- Resumen -->
                <div class="dashboard-card">
                    <h3>Resumen de Actividad</h3>
                    <div class="system-stats">
                        <div class="stat-item">
                            <span class="stat-value" id="totalVideos">0</span>
                            <span class="stat-label">Videos Subidos</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value" id="totalProductos">0</span>
                            <span class="stat-label">Productos en Venta</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-value">0</span>
                            <span class="stat-label">Visitas Totales</span>
                        </div>
                    </div>
                </div>

                <!-- Formulario para subir video -->
                <div id="subir-video" class="dashboard-card">
                    <h3>Subir Nuevo Video</h3>
                    <form id="form-subir-video" enctype="multipart/form-data">
                        <label for="titulo">Título del video:</label>
                        <input type="text" name="titulo" id="titulo" required>

                        <label for="descripcion">Descripción:</label>
                        <textarea name="descripcion" id="descripcion" rows="4" required></textarea>

                        <label for="url_video">URL del video (opcional):</label>
                        <input type="url" name="url_video" id="url_video" placeholder="Ej. https://youtube.com/watch?v=...">

                        <label for="video">O selecciona un archivo:</label>
                        <input type="file" name="video" id="video" accept="video/*">

                        <button type="submit" class="btn-action">Subir Video</button>
                    </form>
                </div>

                <!-- Listado de videos -->
                <div id="videos" class="dashboard-card">
                    <h3>Mis Videos</h3>
                    <div id="lista-videos" class="video-grid">
                        <!-- Aquí se cargarán los videos dinámicamente -->
                    </div>
                </div>

                <!-- Agregar Producto -->
                <div id="agregar-producto" class="dashboard-card">
                    <h3>Agregar Nuevo Producto</h3>
                    <form id="form-agregar-producto" enctype="multipart/form-data">
                        <label for="nombre-producto">Nombre del producto:</label>
                        <input type="text" name="nombre" id="nombre-producto" required>

                        <label for="descripcion-producto">Descripción:</label>
                        <textarea name="descripcion" id="descripcion-producto" rows="4" required></textarea>

                        <label for="precio-producto">Precio:</label>
                        <input type="number" name="precio" id="precio-producto" step="0.01" required>

                        <label for="stock-producto">Stock:</label>
                        <input type="number" name="stock" id="stock-producto" value="0" min="0" required>

                        <label for="imagen-producto">Imagen del producto:</label>
                        <input type="file" name="imagen" id="imagen-producto" accept="image/*" required>

                        <button type="submit" class="btn-action">Agregar Producto</button>
                    </form>
                </div>

                <!-- Listado de productos -->
                <div id="productos" class="dashboard-card">
                    <h3>Mis Productos</h3>
                    <div id="lista-productos" class="product-grid">
                        <!-- Aquí se cargarán los productos dinámicamente -->
                    </div>
                </div>

                <!-- Sección de Pedidos -->
                <div id="pedidos" class="dashboard-card">
                    <h3>Pedidos Recibidos</h3>
                    <div class="pedidos-container">
                        <div class="pedidos-lista">
                            <table class="pedidos-tabla">
                                <thead>
                                    <tr>
                                        <th>ID Pedido</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Productos</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody id="lista-pedidos">
                                    <?php
                                    require_once(__DIR__ . '/../../controllers/ventaController.php');
                                    $controller = new VentaController();
                                    $ventas = $controller->obtenerVentas();
                                    
                                    foreach ($ventas as $venta) {
                                        echo '<tr>';
                                        echo '<td>' . $venta->getIdVenta() . '</td>';
                                        echo '<td>' . date('d/m/Y H:i', strtotime($venta->getFechaVenta())) . '</td>';
                                        echo '<td>' . $venta->getNombreUsuario() . '<br><small>' . $venta->getCorreo() . '</small></td>';
                                        echo '<td>' . $venta->getNombreProducto() . '<br><small>Cantidad: ' . $venta->getCantidad() . '</small></td>';
                                        echo '<td>$' . number_format($venta->getTotal(), 2) . '</td>';
                                        echo '<td><span class="status-badge ' . $venta->getStatus() . '">' . $venta->getStatus() . '</span></td>';
                                        echo '<td>
                                            <button class="btn-accion" onclick="verDetalles(' . $venta->getIdVenta() . ')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn-accion" onclick="actualizarEstado(' . $venta->getIdVenta() . ')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php include('../components/theme-toggle.php'); ?>
    <script src="../../assets/js/dashboard.js"></script>
</body>
</html>