async function votar(idComentario, tipoVoto) {
    try {
        const res = await fetch('../../controllers/votosController.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_comentario=${idComentario}&tipo_voto=${tipoVoto}`
        });

        const data = await res.json();

        if (data.success) {
            document.getElementById(`votos-${idComentario}`).innerText = data.nuevoVoto;
            document.querySelectorAll(`.vote-btn[data-id='${idComentario}']`).forEach(btn => btn.disabled = true);
        } else {
            alert(data.message);
        }
    } catch (err) {
        console.error("Error en la votación:", err);
    }
}

function cambiarOrden() {
    const ordenSeleccionado = document.getElementById("ordenComentarios").value;
    const urlParams = new URLSearchParams(window.location.search);
    
    urlParams.set("orden", ordenSeleccionado);
    window.location.search = urlParams.toString();
}

function mostrarFormulario(idComentario) {
    document.getElementById(`form-${idComentario}`).style.display = 'block';
}

function abrirReporte(idComentario) {
    document.getElementById(`modal-reporte-${idComentario}`).style.display = 'block';
}

function cerrarReporte(idComentario) {
    document.getElementById(`modal-reporte-${idComentario}`).style.display = 'none';
}
