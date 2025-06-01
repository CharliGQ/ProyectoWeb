<?php
require_once('../models/signupModel.php');
require_once('../controllers/signupController.php');

$crud = new CrudUsuario();
$usuario = new Usuario();

if (isset($_POST['registrar'])) {
    $usuario->setNombreUsuario($_POST['nombre_usuario']);
    $usuario->setCorreo($_POST['correo']);
    $usuario->setContrasenia($_POST['contrasenia']);
    $usuario->setRol('registrado'); // Por defecto, todos los usuarios nuevos son 'registrado'
    
    $crud->insertar($usuario);
    header('Location: ../views/login.php');
}
?>

<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <a href="../../index.php" class="btn-back">
            <img src="../assets/img/arrow-left.svg" alt="Regresar">
        </a>
        <div class="auth-card">
            <div class="auth-header">
                <h1>Crear Cuenta</h1>
                <p>Completa el formulario para registrarte</p>
            </div>
            
            <form class="auth-form" action="" method="POST">
                <div class="form-group">
                    <label class="form-label" for="nombre">Nombre Completo</label>
                    <input type="text" class="form-control" id="nombre" name="nombre_usuario" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="correo" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="contrasenia" required>
                </div>
                
                <button type="submit" class="btn-auth" name="registrar">Registrarse</button>
                
                <div class="divider">
                    <span>o</span>
                </div>
                
                <button type="button" class="btn-google">
                    <img src="../assets/img/google.svg" alt="Google Icon">
                    Registrarse con Google
                </button>
            </form>
            
            <div class="auth-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Inicia sesión aquí</a></p>
            </div>
        </div>
    </div>
    <?php include('components/theme-toggle.php'); ?>
</body>
</html> 