function cargarVista(pagina) {
    if (!pagina || pagina === '#' || pagina.trim() === "") return;

    const contenido = document.getElementById('main-content');
    contenido.innerHTML = "<p>Cargando sección...</p>";

    fetch('views/' + pagina)
        .then(response => {
            if (!response.ok) throw new Error('No se encontró el archivo: views/' + pagina);
            return response.text();
        })
        .then(html => {
            // 1. Inyectamos el HTML
            contenido.innerHTML = html;

            // 2. BUSCAMOS Y EJECUTAMOS SCRIPTS MANUALMENTE
            // Esto es lo que permite que el botón "Guardar" funcione
            const scripts = contenido.querySelectorAll("script");
            scripts.forEach(script => {
                const nuevoScript = document.createElement("script");
                if (script.src) {
                    nuevoScript.src = script.src;
                } else {
                    nuevoScript.textContent = script.textContent;
                }
                document.body.appendChild(nuevoScript);
                // Limpiamos el DOM para no llenar de scripts repetidos
                document.body.removeChild(nuevoScript);
            });
        })
        .catch(err => {
            console.error(err);
            contenido.innerHTML = `<div class="alert-danger">
                <h3>Error 404</h3>
                <p>No se pudo cargar la vista <b>${pagina}</b>.</p>
            </div>`;
        });
	
	if(window.activarMenu) {
        window.activarMenu(url);
    }
}


document.addEventListener('click', e => {
    // Buscamos si el clic fue en un enlace o dentro de un enlace con la clase nav-link
    const link = e.target.closest('.nav-link');
    
    if (link) {
        e.preventDefault();
        const vista = link.getAttribute('data-view');
        if (vista && vista !== '#') {
            cargarVista(vista);
        }
    }
});

function filtrarTabla() {
    // 1. Obtener el texto de búsqueda
    const input = document.getElementById("busqueda");
    const filter = input.value.toLowerCase();
    const table = document.getElementById("tabla-usuarios");
    const tr = table.getElementsByTagName("tr");

    // 2. Recorrer todas las filas de la tabla (saltando el encabezado)
    for (let i = 1; i < tr.length; i++) {
        let mostrar = false;
        const td = tr[i].getElementsByTagName("td");
        
        // 3. Buscar en las columnas de Nombre y Usuario
        for (let j = 0; j < td.length - 1; j++) { // -1 para no buscar en la columna de botones
            if (td[j]) {
                const textoColumna = td[j].textContent || td[j].innerText;
                if (textoColumna.toLowerCase().indexOf(filter) > -1) {
                    mostrar = true;
                    break;
                }
            }
        }
        
        // 4. Mostrar u ocultar la fila
        tr[i].style.display = mostrar ? "" : "none";
    }
}

function eliminarFila(id, tipo) {
    if(confirm(`¿Estás seguro de eliminar este ${tipo}?`)) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('tipo', tipo);

        fetch('server/eliminar_general.php', {
            method: 'POST',
            body: formData
        })
		
        .then(res => res.text())
        .then(data => {
            alert(data);
            // Recargamos la vista actual para ver los cambios
            cargarVista(tipo === 'usuario' ? 'usuarios.php' : 'crear_rol.php');
        });
    }
}


function ActivarFila(id, tipo) {
    if(confirm(`¿Estás seguro de activar este ${tipo}?`)) {
        const formData = new FormData();
        formData.append('id', id);
        formData.append('tipo', tipo);

        fetch('server/activar_general.php', {
            method: 'POST',
            body: formData
        })
		
        .then(res => res.text())
        .then(data => {
            alert(data);
            // Recargamos la vista actual para ver los cambios
            cargarVista(tipo === 'usuario' ? 'usuarios.php' : 'crear_rol.php');
        });
    }
}


