<div class="contenedor">
    <h3>Registrar Nuevo Curso</h3>
    <form id="form-curso-crear">
        <div class="form-group">
            <label>Nombre del Curso:</label>
            <input type="text" name="nombre_curso" required placeholder="Ej: Programación Avanzada">
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <input type="text" name="descripcion" placeholder="Breve descripción del curso">
        </div>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" required>
            </div>
            <div style="flex: 1;">
                <label>Duración (Horas):</label>
                <input type="text" name="duracion" placeholder="Ej: 40" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="button" onclick="guardarCurso()" class="btn-success">Guardar Curso</button>
            <button type="button" onclick="cargarVista('cursos.php')" class="btn-back">Cancelar</button>
        </div>
    </form>
</div>

<script>
window.guardarCurso = function() {
    const form = document.getElementById('form-curso-crear');
    if(!form.checkValidity()) { alert("Completa los campos obligatorios"); return; }

    const datos = new FormData(form);
    datos.append('accion', 'crear'); // Identificador para el backend

    fetch('server/cursos_acciones.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(data => {
        alert(data);
        cargarVista('cursos.php');
    });
}
</script>