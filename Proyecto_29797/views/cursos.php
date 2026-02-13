<div class="contenedor">
    <div class="header-complex">
        
        <div class="header-left">
            <h2>Gesti&oacute;n de Cursos</h2>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-bar-advanced no-print">
                    <select id="criterioCurso">
                        <option value="nombre">Nombre Curso</option>
                        <option value="descripcion">Descripci&oacute;n</option>
                    </select>
                    
                    <input type="text" id="terminoCurso" placeholder="Buscar curso..." 
                           onkeyup="if(event.key === 'Enter') buscarCursos(1)">
                    
                    <button class="btn-change" onclick="buscarCursos(1)">&#128269;</button>

                    <select id="limiteCursos" onchange="buscarCursos(1)" 
                            style="margin-left: 20px; border: 1px solid #ccc; border-radius: 4px; font-weight:bold; cursor:pointer; padding: 5px;">
                        <option value="5">Ver 5</option>
                        <option value="10" selected>Ver 10</option>
                        <option value="20">Ver 20</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="header-right no-print">
            <div class="btn-group-top">
                <button class="btn-success" onclick="abrirModal('cursos_crear.php')">&#10010; Nuevo Curso</button>
            </div>
        </div>
    </div>

    <table class="tabla-gestion" id="tablaCursosResultados">
        <thead>
            <tr>
                <th>Nombre del Curso</th>
                <th>Descripci&oacute;n</th>
                <th style="width:120px; text-align:center;">Inicio</th>
                <th style="width:80px; text-align:center;">Duraci&oacute;n</th>
                <th class="acciones-col no-print" style="text-align: center !important; display: table-cell !important;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="5" style="text-align:center;">Cargando cursos...</td></tr>
        </tbody>
    </table>
</div>

<script>
// Iniciar carga
setTimeout(() => { buscarCursos(1); }, 100);

function buscarCursos(pagina = 1) {
    const criterio = document.getElementById('criterioCurso').value;
    const termino = document.getElementById('terminoCurso').value;
    const limite = document.getElementById('limiteCursos').value;

    const d = new FormData();
    d.append('tipo', 'cursos_avanzado');
    d.append('criterio', criterio);
    d.append('termino', termino);
    d.append('pagina', pagina);
    d.append('limite', limite);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        document.querySelector('#tablaCursosResultados tbody').innerHTML = html;
    })
    .catch(err => console.error("Error cargando cursos: " + err));
}

	
	function verAlumnosCurso(id) {
    // Guardamos el ID temporalmente para que la vista lo lea
    localStorage.setItem('curso_actual_id', id);
    // Cargamos la vista limpia (sin el ?id= que da error 404)
    cargarVista('cursos_alumnos.php');
}
	
	// Función para abrir la gestión de alumnos pasando el ID
function cargarAlumnos(idCurso) {
    // Verificamos que el ID no sea undefined o 0
    if (!idCurso) {
        alert("Error: ID de curso no identificado");
        return;
    }

    // Ruta relativa: Como estás en index.php, la carpeta views está ahí mismo.
    fetch("views/cursos_alumnos.php?id=" + idCurso)
        .then(response => {
            if (!response.ok) {
                throw new Error("No se encontró el archivo");
            }
            return response.text();
        })
        .then(html => {
            document.getElementById("main-content").innerHTML = html;
        })
        .catch(error => {
            console.error(error);
            alert("Error al cargar la vista de alumnos. Revisa la consola.");
        });
}
</script>