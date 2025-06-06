<?php
session_start();
require_once '../controllers/productoController.php';
$crudProducto = new CrudProducto();
$productos = $crudProducto->mostrar();
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .producto-card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .producto-card img {
            width: 100%;
            height: 250px;
            object-fit: contain;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.5rem;
        }

        .producto-card h3 {
            color: var(--text-color);
            font-size: 1.4rem;
            margin: 0;
        }

        .producto-card p {
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.5;
        }

        .producto-card strong {
            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .btn-agregar-carrito {
            background: var(--primary-color);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="section-title">Productos</h1>
        <div class="productos-grid">
            <?php foreach ($productos as $producto): ?>
                <div class="producto-card">
                    <?php if ($producto->getImagenUrl()): ?>
                        <?php 
                        $img = $producto->getImagenUrl();
                        // Si la ruta no empieza con '/', la ajustamos
                        if (strpos($img, '/uploads/') === false) {
                            $img = '/ProyectoWeb/uploads/productos/' . basename($img);
                        }
                        ?>
                        <img src="<?php echo $img; ?>" alt="<?php echo htmlspecialchars($producto->getNombre()); ?>">
                    <?php endif; ?>
                    <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                    <p><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                    <p><strong>$<?php echo number_format($producto->getPrecio(), 2); ?></strong></p>
                    <p>Stock: <?php echo (int)$producto->getStock(); ?></p>
                    <button class="btn btn-primary btn-agregar-carrito" data-id="<?php echo $producto->getIdProducto(); ?>">Agregar al carrito</button>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="dashboard/registrado.php" class="btn-back">
            <img src="../assets/img/arrow-left.svg" alt="Regresar">
        </a>
    </div>
    <?php include('components/theme-toggle.php'); ?>
    <aside id="cart-sidebar" class="cart-sidebar">
        <div class="cart-header">
            <h2>Carrito</h2>
        </div>
        <div id="cart-content" class="cart-content">
            <p>Cargando carrito...</p>
        </div>
        <div class="cart-footer">
            <strong>Total: $<span id="cart-total">0.00</span></strong>
            <a href="checkout.php" class="btn btn-primary" style="display: block; margin-top: 1rem; text-align: center;">Proceder al Pago</a>
        </div>
    </aside>
    <style>
    .container {
        margin-right: 340px; /* Espacio para el sidebar */
    }
    .cart-sidebar {
        position: fixed;
        top: 0;
        right: 0;
        width: 340px;
        height: 100vh;
        background: var(--card-bg);
        box-shadow: -2px 0 8px rgba(0,0,0,0.08);
        z-index: 1100;
        display: flex;
        flex-direction: column;
    }
    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem 0.5rem 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    .cart-content {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.5rem;
    }
    .cart-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 0.5rem;
    }
    .cart-item img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 6px;
        background: var(--input-bg);
    }
    .cart-item-info {
        flex: 1;
    }
    .cart-item-title {
        font-weight: 600;
        color: var(--text-color);
        margin-bottom: 0.2rem;
        font-size: 1.1rem;
    }
    [data-theme="dark"] .cart-item-title {
        color: var(--white);
    }
    .cart-item-qty {
        color: var(--text-secondary);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .qty-btn {
        background: var(--primary-color);
        color: var(--white);
        border: none;
        border-radius: 4px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.2s;
    }
    .qty-btn:hover {
        background: var(--primary-dark);
    }
    .qty-btn:disabled {
        background: var(--text-secondary);
        cursor: not-allowed;
    }
    .btn-eliminar {
        background: var(--error-bg);
        color: var(--error-color);
        border: none;
        border-radius: 4px;
        padding: 0.25rem 0.5rem;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: 0.5rem;
    }
    .btn-eliminar:hover {
        background: var(--error-color);
        color: var(--white);
    }
    .cart-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border-color);
        background: var(--card-bg);
        font-size: 1.1rem;
        text-align: right;
    }
    @media (max-width: 768px) {
        .container {
            margin-right: 0;
        }
        .cart-sidebar {
            position: static;
            width: 100%;
            height: auto;
            margin-top: 2rem;
        }
    }
    </style>
    <script>
    function cargarCarrito() {
        fetch('../controllers/carritoController.php?action=ver')
        .then(res => res.json())
        .then(data => {
            const cartContent = document.getElementById('cart-content');
            const cartTotal = document.getElementById('cart-total');
            if (!data.success || !data.productos.length) {
                cartContent.innerHTML = '<p>El carrito está vacío.</p>';
                cartTotal.textContent = '0.00';
                return;
            }
            cartContent.innerHTML = data.productos.map(prod => `
                <div class="cart-item">
                    <img src="${prod.imagen_url ? (prod.imagen_url.startsWith('/') ? prod.imagen_url : '/ProyectoWeb/uploads/productos/' + prod.imagen_url.split('/').pop()) : ''}" alt="${prod.nombre}">
                    <div class="cart-item-info">
                        <div class="cart-item-title">${prod.nombre}</div>
                        <div class="cart-item-qty">
                            <div class="qty-controls">
                                <button class="qty-btn" onclick="actualizarCantidad(${prod.id_producto}, ${prod.cantidad - 1})" ${prod.cantidad <= 1 ? 'disabled' : ''}>-</button>
                                <span>${prod.cantidad}</span>
                                <button class="qty-btn" onclick="actualizarCantidad(${prod.id_producto}, ${prod.cantidad + 1})">+</button>
                            </div>
                            <span>| $${parseFloat(prod.precio_unitario).toFixed(2)}</span>
                            <button class="btn-eliminar" onclick="eliminarProducto(${prod.id_producto})">Eliminar</button>
                        </div>
                    </div>
                </div>
            `).join('');
            cartTotal.textContent = parseFloat(data.total).toFixed(2);
        });
    }

    function eliminarProducto(id_producto) {
        if (!confirm('¿Estás seguro de que deseas eliminar este producto del carrito?')) {
            return;
        }

        fetch('../controllers/carritoController.php?action=eliminar', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ id_producto })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                cargarCarrito();
            } else {
                alert(data.message || 'Error al eliminar el producto');
            }
        })
        .catch(() => alert('Error al eliminar el producto'));
    }

    function actualizarCantidad(id_producto, nuevaCantidad) {
        if (nuevaCantidad < 1) return;
        
        fetch('../controllers/carritoController.php?action=actualizar', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: new URLSearchParams({ 
                id_producto: id_producto,
                cantidad: nuevaCantidad
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                cargarCarrito();
            } else {
                alert(data.message || 'Error al actualizar la cantidad');
            }
        })
        .catch(() => alert('Error al actualizar la cantidad'));
    }

    document.querySelectorAll('.btn-agregar-carrito').forEach(btn => {
        btn.addEventListener('click', function() {
            const id_producto = this.getAttribute('data-id');
            fetch('../controllers/carritoController.php?action=agregar', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: new URLSearchParams({ id_producto })
            })
            .then(res => res.json())
            .then(data => {
                cargarCarrito();
            })
            .catch(() => alert('Error al agregar al carrito.'));
        });
    });
    // Cargar carrito al inicio
    cargarCarrito();
    </script>
</body>
</html> 