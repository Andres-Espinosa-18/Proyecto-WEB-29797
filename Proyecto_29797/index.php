<?php
ini_set('display_errors', 1);
session_start();
// 5. LOGIN: Si no hay sesión, al login.
if (!isset($_SESSION['id_usuario'])) {
    include 'views/login.php'; 
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Académico</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

    <header id="navbar-container">
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main id="main-content" class="contenedor">
        <p>Cargando...</p>
    </main>

    <div id="modal-container" class="modal-overlay">
        <div class="modal-content">
            <span class="close-modal" onclick="cerrarModal()">&times;</span>
            <div id="modal-body"></div>
        </div>
    </div>

    <script src="scripts/javascript.js"></script>
    <script src="scripts/spa.js"></script>
    <script>
        // Cargar vista inicial
        document.addEventListener('DOMContentLoaded', () => cargarVista('principal.php'));
    </script>
<script>
// --- FUNCIONES GLOBALES PARA GESTIÓN DE ALUMNOS EN CURSOS ---

// 1. Cargar la tabla de inscritos
function cargarTablaInscritos() {
    // Leemos el ID del input oculto que pusimos en el HTML
    const inputId = document.getElementById('idCursoActual');
    if (!inputId) return; // Si no estamos en la vista correcta, no hace nada

    const idCurso = inputId.value;
    const tabla = document.getElementById('tablaInscritos');
    
    if(tabla) {
        tabla.innerHTML = '<tr><td colspan="3" style="text-align:center">Cargando...</td></tr>';
        
        fetch(`server/obtener_alumnos_curso.php?accion=listar&id_curso=${idCurso}`)
        .then(r => r.text())
        .then(html => {
            tabla.innerHTML = html;
        });
    }
}

// 2. Inscribir desde el Select
function inscribirDesdeSelect() {
    const inputId = document.getElementById('idCursoActual');
    const select = document.getElementById('selectAlumno');
    
    if (!inputId || !select) return;

    const idCurso = inputId.value;
    const idEstudiante = select.value;

    if (!idEstudiante) {
        alert("Seleccione un alumno primero.");
        return;
    }

    fetch(`server/obtener_alumnos_curso.php?accion=inscribir&id_estudiante=${idEstudiante}&id_curso=${idCurso}`)
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() === 'ok') {
            alert("Alumno inscrito correctamente.");
            // Recargamos la vista para actualizar select y tabla
            cargarAlumnos(idCurso); 
        } else {
            alert("Error del servidor: " + resp);
        }
    });
}

// 3. Eliminar inscripción
function eliminarInscripcion(idNota) {
    if(!confirm("Seguro de retirar al alumno")) return;
    
    const inputId = document.getElementById('idCursoActual');
    const idCurso = inputId ? inputId.value : 0;

    fetch(`server/obtener_alumnos_curso.php?accion=eliminar&id_nota=${idNota}`)
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() === 'ok') {
            // Recargamos para actualizar
            if(idCurso > 0) cargarAlumnos(idCurso);
        } else {
            alert("Error al eliminar");
        }
    });
}

// Función auxiliar para recargar la vista completa (útil tras inscribir)
function cargarAlumnos(idCurso) {
    fetch("views/cursos_alumnos.php?id=" + idCurso)
    .then(r => r.text())
    .then(html => {
        document.getElementById("main-content").innerHTML = html;
        // Forzamos la carga de la tabla tras inyectar el HTML
        setTimeout(cargarTablaInscritos, 100); 
    });
}
</script>
<script>
// --- LÓGICA DE ESTUDIANTES (GLOBAL) ---

function buscarEstudiantes() {
    // 1. Verificar si estamos en la vista correcta
    const tbody = document.getElementById('tbody-estudiantes');
    if (!tbody) return; // Si no hay tabla, no hacemos nada

    // 2. Obtener valores de los filtros
    const criterio = document.getElementById('criterioEst') ? document.getElementById('criterioEst').value : 'nombre';
    const termino = document.getElementById('terminoEst') ? document.getElementById('terminoEst').value : '';
    const limite = document.getElementById('limiteEst') ? document.getElementById('limiteEst').value : '10';

    // 3. Petición al servidor (Ruta relativa desde index.php)
    // Agregamos un número aleatorio (?v=...) para que no se guarde en caché
    const url = `server/busqueda_estudiantes.php?criterio=${criterio}&term=${termino}&limite=${limite}&v=${Date.now()}`;

    fetch(url)
    .then(r => r.text())
    .then(html => {
        if(html.trim() === "") {
             tbody.innerHTML = '<tr><td colspan="6" class="text-center">Error: El archivo del servidor está vacío.</td></tr>';
        } else {
             tbody.innerHTML = html;
        }
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="6" class="text-center" style="color:red;">Error de conexión. Revisa la consola (F12).</td></tr>';
    });
}

// Funciones de los botones de acción
// 2. Abrir modal de NOTAS
function verNotasAdmin(id) {
    // CAMBIO: Usamos cargarVista para ir a "otra página" completa
    cargarVista('estudiantes_notas.php?id=' + id);
}
function editarEstudiante(id) {
    // Aquí estaba el error, ahora apunta al archivo correcto:
    abrirModal('estudiantes_editar.php?id=' + id);
}
function eliminarEstudiante(id) {
    if(!confirm("Seguro de inactivar este estudiante")) return;
    
    const d = new FormData(); 
    d.append('id', id); 
    d.append('tipo', 'estudiante'); // El tipo que procesa eliminar_general.php
    
    fetch('server/eliminar_general.php', { method:'POST', body:d })
    .then(r => r.text())
    .then(mensaje => {
        // Refrescamos la tabla inmediatamente para ver el cambio de botón
        buscarEstudiantes(); 
    });
}
	
	function activarEstudiante(id) {
    if(!confirm("Deseas reactivar este estudiante")) return;
    
    const d = new FormData();
    d.append('id', id);
    d.append('tipo', 'estudiante');

    fetch('server/activar_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(mensaje => {
        buscarEstudiantes(); // Refresco instantáneo
    });
}
	
	function editarCurso(id) {
    // Abrimos el modal con la vista de actualización pasando el ID por GET
    abrirModal('cursos_actualizar.php?id=' + id);
}
	
	function updCurso() {
    const d = new FormData(document.getElementById('form-curso-edit')); d.append('accion', 'actualizar');
    fetch('server/cursos_acciones.php', {method:'POST', body:d}).then(r=>r.text()).then(m=>{ alert(m); cerrarModal(); cargarVista('cursos.php'); });
}
	// Función para Inactivar (Botón Eliminar)
function eliminarCurso(id) {
    if(!confirm("Seguro de inactivar este curso")) return;
    
    const d = new FormData(); 
    d.append('id', id); 
    d.append('tipo', 'curso');
    
    fetch('server/eliminar_general.php', { method:'POST', body:d })
    .then(r => r.text())
    .then(mensaje => {
        // Si el servidor hizo el UPDATE SET estado = 0
        // simplemente refrescamos la tabla para que el PHP dibuje el botón "Activar"
        buscarCursos(1); 
    });
}

// Función para Reactivar (Botón Activar)
function activarCurso(id) {
    if(!confirm("Deseas reactivar este curso")) return;
    
    const d = new FormData();
    d.append('id', id);
    d.append('tipo', 'curso');

    fetch('server/activar_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(mensaje => {
     
           buscarCursos(1); // Refresco instantáneo
        
    });
}
	
	// Función global para cerrar CUALQUIER modal

function guardarEstudiante() {
    const form = document.getElementById('formCrearEstudiante');
    const data = new FormData(form);
    
    // Agregamos la acción manualmente
    data.append('accion', 'crear');

    fetch('server/estudiantes_acciones.php', {
        method: 'POST',
        body: data
    })
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() == 'ok') {
            alert("Estudiante creado con éxito");
            cerrarModal();
            buscarEstudiantes(); // Recargar la tabla de atrás
        } else {
            alert("Error: " + resp);
        }
    });
}
	
function actualizarEstudiante() {
    const form = document.getElementById('formEditarEstudiante');
    const data = new FormData(form);
    
    // Enviamos a estudiantes_acciones.php (Asegúrate de tener este archivo creado como vimos antes)
    fetch('server/estudiantes_acciones.php', {
        method: 'POST',
        body: data
    })
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() == 'ok') {
            alert("Estudiante actualizado correctamente");
            cerrarModal();       // Cierra el modal
            buscarEstudiantes(); // Recarga la tabla de atrás
        } else {
            alert("Error al actualizar: " + resp);
        }
    })
    .catch(err => alert("Error de conexión: " + err));
}
	
function actualizarPerfil() {
    const p1 = document.getElementById('p1').value;
    const p2 = document.getElementById('p2').value;

    if (p1 !== "" || p2 !== "") {
        if (p1 !== p2) { alert("Las contraseñas no coinciden"); return; }
    }

    const d = new FormData(document.getElementById('formPerfil'));
    fetch('server/perfil_update.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() === 'ok') {
            alert("Perfil actualizado.");
            cerrarModal();
        } else {
            alert("Error: " + resp);
        }
    });
}
	
	
</script>
</body>
</html>