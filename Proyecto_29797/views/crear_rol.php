<?php
// Corrección de caracteres raros ()
header('Content-Type: text/html; charset=UTF-8');
?>
<div class="contenedor">
    <div class="header-complex">
        
        <div class="header-left">
            <h2>Gesti&oacute;n de Roles</h2>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-bar-advanced no-print">
                    <select id="criterioRol">
                        <option value="general">Todo</option>
                        <option value="nombre">Nombre</option>
                        <option value="descripcion">Descripci&oacute;n</option>
                    </select>
                    
                    <input type="text" id="terminoRol" placeholder="Buscar..." 
                           onkeyup="if(event.key === 'Enter') buscarRoles(1)">
                    
                    <button class="btn-change" onclick="buscarRoles(1)">&#128269;</button>

                    <select id="limiteRoles" onchange="buscarRoles(1)" 
                            style="margin-left: 20px; border: 1px solid #ccc; border-radius: 4px; width:auto; font-weight:bold; cursor:pointer; padding: 5px;">
                        <option value="5">Ver 5</option>
                        <option value="10" selected>Ver 10</option>
                        <option value="20">Ver 20</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="header-right no-print">
            <div class="btn-group-top">
                <button class="btn-success" onclick="abrirModal('roles_crear.php')">&#10010; Nuevo Rol</button>
                <button class="btn-change" onclick="window.print()">&#128462; PDF</button>
            </div>
        </div>

    </div>

    <table class="tabla-gestion" id="tablaRolesResultados">
        <thead>
            <tr>
                <th style="width:50px;">ID</th>
                <th>Nombre del Rol</th>
                <th>Descripci&oacute;n</th>
                <th class="acciones-col no-print">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-roles">
            <tr><td colspan="4" style="text-align:center;">Cargando roles...</td></tr>
        </tbody>
    </table>
    
    <div id="paginacionRoles" class="no-print" style="margin-top:10px; text-align:center;"></div>
</div>

<script>
// --- CORRECCIÓN DEL "CARGANDO..." ---
// Al ser una vista dinámica, no usamos DOMContentLoaded. 
// Ejecutamos la búsqueda directamente con un pequeño retraso para asegurar que el HTML existe.
setTimeout(function() {
    buscarRoles(1);
}, 100);

function buscarRoles(pagina) {
    // Si no se pasa página, es la 1
    if (!pagina) pagina = 1;

    const criterio = document.getElementById('criterioRol').value;
    const termino = document.getElementById('terminoRol').value;
    const limite = document.getElementById('limiteRoles').value;

    const d = new FormData();
    d.append('tipo', 'rol_avanzado');
    d.append('criterio', criterio);
    d.append('termino', termino);
    d.append('pagina', pagina);
    d.append('limite', limite);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        const tbody = document.querySelector('#tablaRolesResultados tbody');
        if(tbody) tbody.innerHTML = html;
    })
    .catch(err => console.error("Error: " + err));
}

function eliminarRol(id) {
    if(!confirm("Seguro de eliminar este rol")) return;
    const d = new FormData(); 
    d.append('id', id); 
    d.append('tipo', 'rol');
    
    fetch('server/eliminar_general.php', { method:'POST', body:d })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        buscarRoles(1);
    });
}
</script>