<div class="contenedor">
    <h3>Registrar Nuevo Estudiante</h3>
    <form id="form-est-crear">
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            
            <div>
                <label>Nombre Completo:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            
            <div>
                <label>Cédula:</label>
                <input type="text" name="cedula" class="form-control" required>
            </div>

            <div>
                <label>Usuario para Login:</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div>
                <label>Contraseña:</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div>
                <label>Carrera:</label>
                <input type="text" name="carrera" class="form-control">
            </div>

            <div>
                <label>Fecha Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" class="form-control">
            </div>

            <div>
                <label>Correo Electrónico:</label>
                <input type="email" name="correo" class="form-control">
            </div>

            <div>
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control">
            </div>

            <div style="grid-column: 1 / -1;">
                <label>Dirección:</label>
                <input type="text" name="direccion" class="form-control">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="button" onclick="crearEstudiante()" class="btn-success">Guardar Estudiante</button>
            <button type="button" onclick="cargarVista('estudiantes.php')" class="btn-back">Cancelar</button>
        </div>
    </form>
</div>

<style>
    .form-control { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
</style>

<script>
function crearEstudiante() {
    const form = document.getElementById('form-est-crear');
    if(!form.checkValidity()) { alert("Por favor llena los campos requeridos."); return; }
    
    const datos = new FormData(form);
    datos.append('accion', 'crear');

    fetch('server/estudiantes_acciones.php', { method: 'POST', body: datos })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        if(msg.includes('correctamente')) cargarVista('estudiantes.php');
    });
}
</script>