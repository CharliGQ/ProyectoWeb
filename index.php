<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="app/assets/css/styles.css">
    <link rel="stylesheet" href="app/assets/css/sidebar.css">
    <link rel="stylesheet" href="app/assets/css/theme-toggle.css">
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="app/assets/img/logo-danievil.jpg" alt="Logo" class="logo">
        </div>
        <ul class="sidebar-menu">
            <li><a href="./index.php" class="active">Inicio</a></li>
            <li><a href="#videos">Videos</a></li>
            <li><a href="#productos">Productos</a></li>
            <li><a href="#redes">Redes</a></li>
            <li><a href="#donaciones">Donaciones</a></li>
            <li><a href="#biografia">Biografía</a></li>
            <li><a href="#patrocinios">Patrocinios</a></li>
            <li><a href="#acerca">Acerca</a></li>
        </ul>
        <button class="theme-toggle" id="themeToggle" aria-label="Cambiar tema">
            <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="display: none;">
                <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0-.39.39-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0 .39-.39.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41-.39-.39-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41.39.39 1.03.39 1.41 0l1.06-1.06z"/>
            </svg>
            <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M9.37 5.51c-.18.64-.27 1.31-.27 1.99 0 4.08 3.32 7.4 7.4 7.4.68 0 1.35-.09 1.99-.27C17.45 17.19 14.93 19 12 19c-3.86 0-7-3.14-7-7 0-2.93 1.81-5.45 4.37-6.49zM12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9c0-.46-.04-.92-.1-1.36-.98 1.37-2.58 2.26-4.4 2.26-2.98 0-5.4-2.42-5.4-5.4 0-1.81.89-3.42 2.26-4.4-.44-.06-.9-.1-1.36-.1z"/>
            </svg>
        </button>
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
                <iframe src="https://www.youtube.com/embed/FQXf12DGCDQ" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="videos-secundarios">
                <div class="video-secundario">
                    <iframe src="https://www.youtube.com/embed/2zHpzT7Cq6w" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="video-secundario">
                    <iframe src="https://www.youtube.com/embed/8DrAtmrV3o8" frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
        </section>

        <!-- Sección de videos verticales 
        <section class="videos-verticales">
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div>
            <div class="video-vertical">
                <iframe src="https://youtube.com/shorts/VYcmT9Ph8hI?si=sfprB405aDRDd5cs" frameborder="0" allowfullscreen></iframe>
            </div> 
        </section>
        -->

        <!-- Sección de Productos -->
        <section id="productos" class="section">
            <h2 class="section-title">Productos</h2>
            <div class="productos-grid">
                <div class="producto-card">
                    <img src="app/assets/img/producto1.jpg" alt="Producto 1">
                    <h3>Producto 1</h3>
                    <p>Descripción del producto 1</p>
                    <button class="btn-comprar">Comprar</button>
                </div>
                <div class="producto-card">
                    <img src="app/assets/img/producto2.jpg" alt="Producto 2">
                    <h3>Producto 2</h3>
                    <p>Descripción del producto 2</p>
                    <button class="btn-comprar">Comprar</button>
                </div>
                <!-- Más productos aquí -->
            </div>
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

    <script src="app/assets/js/theme.js"></script>
</body>
</html> 