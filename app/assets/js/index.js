// Función para extraer ID de YouTube
function getYoutubeID(url) {
    const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[7].length == 11) ? match[7] : false;
}

// Cargar vídeos más recientes
async function cargarVideosPublicos() {
    const contenedorDestacado = document.querySelector('.videos-horizontales .video-destacado');
    const contenedorSecundarios = document.querySelector('.videos-horizontales .videos-secundarios');

    try {
        const res = await fetch('app/controllers/homeController.php?action=videos');
        const data = await res.json();

        if (data.success && data.videos.length > 0) {
            contenedorSecundarios.innerHTML = '';

            data.videos.forEach((video, index) => {
                const videoId = getYoutubeID(video.url_video);

                if (!videoId) return;

                const iframeHTML = `
                        <div class="video-secundario">
                            <iframe src="https://www.youtube.com/embed/${videoId}"  
                                    frameborder="0" allowfullscreen></iframe>
                        </div>
                    `;

                if (index === 0) {
                    contenedorDestacado.innerHTML = `
                            <iframe src="https://www.youtube.com/embed/${videoId}"  
                                    frameborder="0" allowfullscreen></iframe>
                        `;
                } else {
                    contenedorSecundarios.insertAdjacentHTML('beforeend', iframeHTML);
                }
            });
        } else {
            contenedorDestacado.innerHTML = '<p>No hay vídeos disponibles.</p>';
        }
    } catch (err) {
        console.error('Error al cargar vídeos:', err);
    }
}

// Cargar productos más recientes
async function cargarProductosRecientes() {
    const contenedor = document.querySelector('.productos-grid');
    try {
        const res = await fetch('app/controllers/homeController.php?action=productos');
        const data = await res.json();

        if (data.success && data.productos.length > 0) {
            contenedor.innerHTML = ''; // Limpiar placeholders

            data.productos.forEach(producto => {
                // Corrige la ruta de la imagen
                let imagenUrl = producto.imagen_url || 'app/assets/img/placeholder.png';
                imagenUrl = imagenUrl.replace('../', ''); // Elimina ../ si viene desde BD

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
            contenedor.innerHTML = '<p class="empty-message">No hay productos disponibles aún.</p>';
        }
    } catch (err) {
        console.error('Error al cargar productos:', err);
    }
}

// Cargar contenido al inicio
window.addEventListener('DOMContentLoaded', () => {
    cargarVideosPublicos();
    cargarProductosRecientes();
});
