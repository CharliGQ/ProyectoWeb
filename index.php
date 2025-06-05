<?php
$currentTheme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'light';
?>
<!DOCTYPE html>
<html lang="es" data-theme="<?php echo $currentTheme; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="app/assets/css/styles.css">
    <link rel="stylesheet" href="app/assets/css/sidebar.css">
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="app/assets/img/logo-danievil.jpg" alt="Logo" class="logo">
        </div>
        <ul class="sidebar-menu">
            <li><a href="./index.php" class="active">Inicio</a></li>
            <li><a href="app/views/productos.php">Productos</a></li>
            <li><a href="app/views/videos.php">Videos</a></li>
            <li><a href="#redes">Redes</a></li>
            <li><a href="#donaciones">Donaciones</a></li>
            <li><a href="#biografia">Biografía</a></li>
            <li><a href="#patrocinios">Patrocinios</a></li>
            <li><a href="#acerca">Acerca</a></li>
        </ul>
        <?php include('app/views/components/theme-toggle.php'); ?>
    </nav>
    <div class="auth-buttons">
        <a href="app/views/login.php" class="btn-login">Iniciar Sesión</a>
        <a href="app/views/signup.php" class="btn-register">Registrarse</a>
    </div>
    <main class="main-content">
        <h1 class="main-title">Videos Destacados</h1>
        <!-- Sección de videos horizontales -->
        <section class="videos-horizontales">
            <div class="video-destacado">
                <!-- El primer vídeo aparecerá aquí dinámicamente -->
            </div>
            <div class="videos-secundarios">
                <!-- Los siguientes vídeos aparecerán aquí -->
            </div>
        </section>

        <!-- Sección de Productos -->
        <section id="productos" class="section">
            <h2 class="section-title">Productos</h2>
            <div class="productos-grid">
                <!-- Aquí se cargarán los productos dinámicamente -->
            </div>
            <!-- Más productos aquí -->
        </section>

        <!-- Sección de Redes Sociales -->
        <section id="redes" class="section">
            <h2 class="section-title">Redes Sociales</h2>
            <div class="redes-grid">
                <a href="#" class="red-social-card">
                    <img src="app/assets/img/youtube.svg" alt="YouTube" class="social-icon">
                    <h3>YouTube</h3>
                    <p>Sígueme en YouTube</p>
                </a>
                <a href="#" class="red-social-card">
                    <img src="app/assets/img/instagram.svg" alt="Instagram" class="social-icon">
                    <h3>Instagram</h3>
                    <p>Sígueme en Instagram</p>
                </a>
                <a href="#" class="red-social-card">
                    <img src="app/assets/img/twitter-x.svg" alt="Twitter" class="social-icon">
                    <h3>X</h3>
                    <p>Sígueme en X</p>
                </a>
            </div>
        </section>

        <!-- Sección de Donaciones -->
        <section id="donaciones" class="section">
            <h2 class="section-title">Donaciones</h2>
            <div class="donaciones-container">
                <p>Si te gusta mi contenido y quieres apoyarme, puedes hacer una donación:</p>
                <div class="metodos-donacion">
                    <div class="metodo-donacion">
                        <img src="app/assets/img/paypal.png" alt="PayPal">
                        <button class="btn-donar">Donar con PayPal</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección de Biografía -->
        <section id="biografia" class="section">
            <h2 class="section-title">Biografía</h2>
            <div class="biografia-container">
                <div class="biografia-imagen">
                    <img src="app/assets/img/perfil.jpg" alt="Foto de perfil">
                </div>
                <div class="biografia-contenido">
                    <h3>Mi Historia</h3>
                    <p>Aquí va tu biografía y tu historia...</p>
                </div>
            </div>
        </section>

        <!-- Sección de Patrocinios -->
        <section id="patrocinios" class="section">
            <h2 class="section-title">Patrocinios</h2>
            <div class="patrocinios-grid">
                <div class="patrocinio-card">
                    <img src="app/assets/img/patrocinador1.png" alt="Patrocinador 1">
                    <h3>Patrocinador 1</h3>
                    <p>Descripción del patrocinador</p>
                </div>
                <!-- Más patrocinadores aquí -->
            </div>
        </section>

        <!-- Sección Acerca -->
        <section id="acerca" class="section">
            <h2 class="section-title">Acerca</h2>
            <div class="acerca-container">
                <h3>¿Quién soy?</h3>
                <p>Información sobre ti y tu proyecto...</p>
                <h3>Contacto</h3>
                <p>Email: tu@email.com</p>
                <p>Teléfono: +1234567890</p>
            </div>
        </section>
    </main>
    <script src="app/assets/js/index.js"></script>
</body>
</html> 