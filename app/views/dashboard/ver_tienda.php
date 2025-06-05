<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'registrado') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <title>Mi Tienda</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>

<h2>Productos Disponibles</h2>
<div id="lista-productos" class="product-grid">
    <!-- Aquí se cargarán los productos dinámicamente -->
</div>

<script>
    async function cargarProductos() {
        const contenedor = document.getElementById('lista-productos');

        try {
            const res = await fetch('../../../app/controllers/homeController.php?action=productos');
            const data = await res.json();

            if (data.success && data.productos.length > 0) {
                data.productos.forEach(producto => {
                    let imagenUrl = producto.imagen_url ? '/' + producto.imagen_url : '/app/assets/img/placeholder.png';

                    contenedor.insertAdjacentHTML('beforeend', `
                        <div class="producto-card">
                            <img src="/ProyectoWeb/${imagenUrl}" alt="${producto.nombre}">
                            <h3>${producto.nombre}</h3>
                            <p>$${parseFloat(producto.precio).toFixed(2)}</p>
                            <button class="btn-comprar">Comprar</button>
                        </div>
                    `);
                });
            } else {
                contenedor.innerHTML = '<p>No hay productos disponibles aún.</p>';
            }
        } catch (err) {
            console.error("Error al cargar productos:", err);
            contenedor.innerHTML = '<p>Error al cargar los productos.</p>';
        }
    }

    window.addEventListener('DOMContentLoaded', () => {
        cargarProductos();
    });
</script>

</body>
</html>