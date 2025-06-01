<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="auth-container">
        <a href="../../index.php" class="btn-back">
            <img src="../assets/img/arrow-left.svg" alt="Regresar">
        </a>
        <div class="auth-card">
            <div class="auth-header">
                <h1>Iniciar Sesión</h1>
                <p>Ingresa tus credenciales para acceder</p>
            </div>
            
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>
            
            <form class="auth-form" action="../controllers/loginController.php" method="POST">
                <div class="form-group">
                    <label class="form-label" for="email">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="password">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-auth">Iniciar Sesión</button>
                
                <div class="divider">
                    <span>o</span>
                </div>
                
                <button type="button" class="btn-google">
                    <img src="../assets/img/google.svg" alt="Google Icon">
                    Continuar con Google
                </button>
            </form>
            
            <div class="auth-footer">
                <p>¿No tienes una cuenta? <a href="signup.php">Regístrate aquí</a></p>
            </div>
        </div>
    </div>
    <?php include('components/theme-toggle.php'); ?>
</body>
</html>