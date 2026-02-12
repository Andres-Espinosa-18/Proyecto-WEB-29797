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
            cargarVista(tipo === 'usuario' ? 'usuarios.php' : tipo === 'rol' ? 'crear_rol.php' : 'cursos.php');
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
            cargarVista(tipo === 'usuario' ? 'usuarios.php' : tipo === 'rol' ? 'crear_rol.php' : 'cursos.php');
        });
    }
}

window.ejecutarBusqueda = function(tipoEntidad) {

    const input = document.getElementById('inputBusqueda');

    const termino = input ? input.value : (document.getElementById('busqUser') ? document.getElementById('busqUser').value : "");
    const fecha = document.getElementById('busqFecha') ? document.getElementById('busqFecha').value : "";


    const tablaActiva = document.querySelector('.tabla-gestion');
    if (!tablaActiva) {
        console.error("No se encontró una tabla con la clase .tabla-gestion");
        return;
    }
    const cuerpoTabla = tablaActiva.querySelector('tbody');

    // 3. Preparamos los datos para el servidor
    const datos = new FormData();
    datos.append('tipo', tipoEntidad);
    datos.append('termino', termino);
    datos.append('fecha', fecha); // Solo se usará en el caso 'auditoria' del PHP

    fetch('server/busqueda_general.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.text())
    .then(html => {
        // Inyectamos los resultados
        cuerpoTabla.innerHTML = html;

        if (tipoEntidad === 'auditoria') {
            if (typeof paginaActualAuditoria !== 'undefined') {
                paginaActualAuditoria = 1;
                actualizarPaginacionAuditoria();
            }
        } else {
            // Para Usuarios, Roles y Cursos usamos la paginación estándar
            if (typeof paginaActual !== 'undefined') {
                paginaActual = 1;
                actualizarPaginacion();
            }
        }

        // Mensaje si no hay resultados
        if (html.trim() === "") {
            cuerpoTabla.innerHTML = `<tr><td colspan="10" style="text-align:center; padding:20px; color:#a0aec0;">
                No se encontraron registros para "${termino}" ${fecha ? ' en la fecha ' + fecha : ''}
            </td></tr>`;
        }
    })
    .catch(err => {
        console.error("Error en el fetch de búsqueda:", err);
    });
}
