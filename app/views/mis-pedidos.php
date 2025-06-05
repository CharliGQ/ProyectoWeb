<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'registrado') {
    header('Location: login.php');
    exit();
}

require_once '../controllers/carritoController.php';
$carritoController = new CarritoController();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1 class="section-title">Mis Pedidos</h1>
        
        <div class="pedidos-container">
            <?php
            $db = Db::conectar();
            $id_usuario = $_SESSION['usuario']['id'];
            
            // Obtener todos los carritos comprados del usuario
            $query = "SELECT c.id_carrito, c.fecha_creacion, v.id_venta, v.total, v.metodo_pago, v.status, v.fecha_venta 
                     FROM carrito_compras c 
                     JOIN ventas v ON c.id_carrito = v.id_carrito 
                     WHERE c.id_usuario = :id_usuario AND c.estado = 'comprado' 
                     ORDER BY v.fecha_venta DESC";
            
            $stmt = $db->prepare($query);
            $stmt->bindValue(':id_usuario', $id_usuario);
            $stmt->execute();
            $pedidos = $stmt->fetchAll();

            if (empty($pedidos)): ?>
                <div class="no-pedidos">
                    <p>No tienes pedidos realizados</p>
                    <a href="productos.php" class="btn btn-primary">Ver Productos</a>
                </div>
            <?php else: ?>
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="pedido-card">
                        <div class="pedido-header">
                            <div class="pedido-info">
                                <h3>Pedido #<?php echo $pedido['id_venta']; ?></h3>
                                <p class="pedido-fecha">Fecha: <?php echo date('d/m/Y H:i', strtotime($pedido['fecha_venta'])); ?></p>
                            </div>
                            <div class="pedido-status <?php echo strtolower($pedido['status']); ?>">
                                <?php echo ucfirst($pedido['status']); ?>
                            </div>
                        </div>
                        
                        <div class="pedido-detalles">
                            <?php
                            // Obtener detalles del pedido
                            $query = "SELECT vd.*, p.nombre, p.imagen_url 
                                     FROM ventas_detalle vd 
                                     JOIN productos p ON vd.id_producto = p.id_producto 
                                     WHERE vd.id_venta = :id_venta";
                            $stmt = $db->prepare($query);
                            $stmt->bindValue(':id_venta', $pedido['id_venta']);
                            $stmt->execute();
                            $detalles = $stmt->fetchAll();
                            
                            foreach ($detalles as $detalle): ?>
                                <div class="pedido-item">
                                    <img src="<?php echo $detalle['imagen_url'] ? (strpos($detalle['imagen_url'], '/') === 0 ? $detalle['imagen_url'] : '/ProyectoWeb/uploads/productos/' . basename($detalle['imagen_url'])) : ''; ?>" 
                                         alt="<?php echo htmlspecialchars($detalle['nombre']); ?>">
                                    <div class="item-info">
                                        <h4><?php echo htmlspecialchars($detalle['nombre']); ?></h4>
                                        <p>Cantidad: <?php echo $detalle['cantidad']; ?></p>
                                        <p>Precio unitario: $<?php echo number_format($detalle['precio_unitario'], 2); ?></p>
                                    </div>
                                    <div class="item-subtotal">
                                        $<?php echo number_format($detalle['cantidad'] * $detalle['precio_unitario'], 2); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="pedido-footer">
                            <div class="metodo-pago">
                                <span>Método de pago: </span>
                                <strong><?php echo ucfirst($pedido['metodo_pago']); ?></strong>
                            </div>
                            <div class="pedido-total">
                                <span>Total: </span>
                                <strong>$<?php echo number_format($pedido['total'], 2); ?></strong>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <a href="dashboard/registrado.php" class="btn-back">
            <img src="../assets/img/arrow-left.svg" alt="Regresar">
        </a>
    </div>

    <style>
    .pedidos-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 1rem;
    }

    .no-pedidos {
        text-align: center;
        padding: 2rem;
        background: var(--card-bg);
        border-radius: 8px;
        box-shadow: var(--shadow);
    }

    .pedido-card {
        background: var(--card-bg);
        border-radius: 8px;
        box-shadow: var(--shadow);
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .pedido-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: var(--hover-bg);
        border-bottom: 1px solid var(--border-color);
    }

    .pedido-info h3 {
        margin: 0;
        color: var(--text-color);
        font-size: 1.1rem;
    }

    .pedido-fecha {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin: 0.1rem 0 0 0;
    }

    .pedido-status {
        padding: 0.35rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .pedido-status.pagado {
        background: var(--success-bg);
        color: var(--success-color);
    }

    .pedido-status.enviado {
        background: var(--info-bg);
        color: var(--info-color);
    }

    .pedido-status.recibido {
        background: var(--primary-bg);
        color: var(--primary-color);
    }

    .pedido-status.cancelado {
        background: var(--error-bg);
        color: var(--error-color);
    }

    .pedido-detalles {
        padding: 0.75rem 1rem;
    }

    .pedido-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }

    .pedido-item:last-child {
        border-bottom: none;
    }

    .pedido-item img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        background: var(--input-bg);
    }

    .item-info {
        flex: 1;
    }

    .item-info h4 {
        margin: 0 0 0.25rem 0;
        color: var(--text-color);
        font-size: 0.95rem;
    }

    .item-info p {
        margin: 0.1rem 0;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .item-subtotal {
        font-weight: 600;
        color: var(--text-color);
        font-size: 0.95rem;
    }

    .pedido-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        background: var(--hover-bg);
        border-top: 1px solid var(--border-color);
        font-size: 0.9rem;
    }

    .metodo-pago {
        color: var(--text-secondary);
    }

    .pedido-total {
        font-size: 1rem;
    }

    .pedido-total strong {
        color: var(--text-color);
    }

    @media (max-width: 768px) {
        .pedido-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.35rem;
        }

        .pedido-footer {
            flex-direction: column;
            gap: 0.35rem;
            text-align: center;
        }

        .pedido-item {
            flex-direction: column;
            text-align: center;
        }

        .item-subtotal {
            margin-top: 0.35rem;
        }
    }
    </style>

    <?php include('components/theme-toggle.php'); ?>
</body>
</html> 