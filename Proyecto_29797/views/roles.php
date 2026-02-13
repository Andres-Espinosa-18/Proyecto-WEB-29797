<div class="contenedor">
    <div class="header-complex">
        
        <div class="header-left">
            <h2>Gestion de Roles</h2>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-bar-advanced no-print">
                    <select id="criterioRol">
                        <option value="general">Todo</option>
                        <option value="nombre">Nombre Rol</option>
                        <option value="descripcion">Descripción</option>
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
            </div>
        </div>

    </div>

    <table class="tabla-gestion" id="tablaRolesResultados">
        <thead>
            <tr>
                <th style="width:50px;">ID</th>
                <th>Nombre del Rol</th>
                <th>Descripción</th>
                <th class="acciones-col no-print">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4" style="text-align:center;">Cargando roles...</td></tr>
        </tbody>
    </table>
    
    <div id="paginacionRoles" class="no-print" style="margin-top:10px; text-align:center;"></div>
</div>

<script>
// Función principal para cargar la tabla
function buscarRoles(pagina = 1) {
    const criterio = document.getElementById('criterioRol').value;
    const termino = document.getElementById('terminoRol').value;
    const limite = document.getElementById('limiteRoles').value;

    const d = new FormData();
    // Importante: 'rol_avanzado' debe estar configurado en tu server/busqueda_general.php
    d.append('tipo', 'rol_avanzado'); 
    d.append('criterio', criterio);
    d.append('termino', termino);
    d.append('pagina', pagina);
    d.append('limite', limite);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        // Pegamos el HTML que nos devuelve el servidor
        document.querySelector('#tablaRolesResultados tbody').innerHTML = html;
    })
    .catch(err => console.error("Error: " + err));
}

// Cargar apenas se abre la vista
document.addEventListener("DOMContentLoaded", function() {
    buscarRoles(1);
});

// Función para eliminar
function eliminarRol(id) {
    if(!confirm("¿Seguro de eliminar este rol?")) return;
    
    const d = new FormData(); 
    d.append('id', id); 
    d.append('tipo', 'rol'); // Esto llama a eliminar_general.php
    
    fetch('server/eliminar_general.php', { method:'POST', body:d })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        buscarRoles(1); // Recargar tabla
    });
}
</script>