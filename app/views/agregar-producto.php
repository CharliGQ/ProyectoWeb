<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'creador') {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Producto</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1>Agregar Nuevo Producto</h1>
            <a href="productos.php" class="btn-back">Volver a Productos</a>
        </div>

        <form id="productoForm" class="form" action="../controllers/productoController.php?action=crear" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre del Producto</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="precio">Precio</label>
                    <input type="number" id="precio" name="precio" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" name="stock" min="0" required>
                </div>
            </div>

            <div class="form-group">
                <label for="imagen">Imagen del Producto</label>
                <input type="file" id="imagen" name="imagen" accept="image/*" required>
                <div id="preview" class="image-preview"></div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">Agregar Producto</button>
                <button type="reset" class="btn-reset">Limpiar Formulario</button>
            </div>
        </form>
    </div>

    <script>
        // Vista previa de la imagen
        document.getElementById('imagen').addEventListener('change', function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '';
            
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            }
        });

        // Validación del formulario
        document.getElementById('productoForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nombre = document.getElementById('nombre').value.trim();
            const descripcion = document.getElementById('descripcion').value.trim();
            const precio = parseFloat(document.getElementById('precio').value);
            const stock = parseInt(document.getElementById('stock').value);
            const imagen = document.getElementById('imagen').files[0];

            if (!nombre || !descripcion || precio <= 0 || stock < 0 || !imagen) {
                alert('Por favor, complete todos los campos correctamente');
                return;
            }

            // Crear FormData y enviar
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto agregado exitosamente');
                    window.location.href = 'productos.php';
                } else {
                    alert('Error al agregar el producto: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar el producto');
            });
        });
    </script>
</body>
</html> 