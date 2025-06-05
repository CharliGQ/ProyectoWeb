<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
require_once '../controllers/perfilController.php';
$perfilController = new PerfilController();
$perfil = $perfilController->obtenerPerfil()['perfil'];
?>
<!DOCTYPE html>
<html lang="es" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/formularios.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">
</head>
<body>
    <div class="form-container">
        <a href="dashboard/registrado.php" class="btn-back">
            <img src="../assets/img/arrow-left.svg" alt="Regresar">
        </a>
        <div class="form-header">
            <h1>Mi Perfil</h1>
        </div>

        <div class="profile-sections">
            <!-- Información del Perfil -->
            <div class="profile-section">
                <h2>Información Personal</h2>
                <form id="perfilForm" class="form" action="../controllers/perfilController.php?action=actualizar" method="POST">
                    <div class="form-group">
                        <label for="nombre_usuario">Nombre de Usuario</label>
                        <input type="text" id="nombre_usuario" name="nombre_usuario" value="<?php echo htmlspecialchars($perfil->getNombreUsuario()); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="correo">Correo Electrónico</label>
                        <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($perfil->getCorreo()); ?>" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Guardar Cambios</button>
                    </div>
                </form>
            </div>

            <!-- Cambiar Contraseña -->
            <div class="profile-section">
                <h2>Cambiar Contraseña</h2>
                <form id="passwordForm" class="form" action="../controllers/perfilController.php?action=actualizarContrasenia" method="POST">
                    <div class="form-group">
                        <label for="nueva_contrasenia">Nueva Contraseña</label>
                        <input type="password" id="nueva_contrasenia" name="nueva_contrasenia" required>
                    </div>

                    <div class="form-group">
                        <label for="confirmar_contrasenia">Confirmar Contraseña</label>
                        <input type="password" id="confirmar_contrasenia" name="confirmar_contrasenia" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-submit">Cambiar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include('components/theme-toggle.php'); ?>

    <script>
        // Manejo del formulario de perfil
        document.getElementById('perfilForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Perfil actualizado exitosamente');
                    location.reload();
                } else {
                    alert('Error al actualizar el perfil: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar el perfil');
            });
        });

        // Manejo del formulario de contraseña
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nuevaContrasenia = document.getElementById('nueva_contrasenia').value;
            const confirmarContrasenia = document.getElementById('confirmar_contrasenia').value;

            if (nuevaContrasenia !== confirmarContrasenia) {
                alert('Las contraseñas no coinciden');
                return;
            }

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contraseña actualizada exitosamente');
                    this.reset();
                } else {
                    alert('Error al actualizar la contraseña: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar la contraseña');
            });
        });
    </script>
</body>
</html> 