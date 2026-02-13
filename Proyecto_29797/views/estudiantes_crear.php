<div class="header-complex">
    <div class="header-left">
        <h2 style="color: var(--primary); font-size: 1.5rem;">Registrar Nuevo Estudiante</h2>
    </div>
</div>

<form id="formCrearEstudiante" onsubmit="event.preventDefault(); guardarEstudiante();">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        <div class="form-group">
            <label>Nombres:</label>
            <input type="text" name="nombre" required class="form-control" style="width:100%; padding:8px;">
        </div>
        <div class="form-group">
            <label>Apellidos:</label>
            <input type="text" name="apellido" class="form-control" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label>Cédula:</label>
            <input type="text" name="cedula" required class="form-control" style="width:100%; padding:8px;">
        </div>
        <div class="form-group">
            <label>Correo:</label>
            <input type="email" name="correo" class="form-control" style="width:100%; padding:8px;">
        </div>

        <div class="form-group">
            <label>Usuario (Login):</label>
            <input type="text" name="usuario" required class="form-control" style="width:100%; padding:8px;">
        </div>
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="clave" required class="form-control" style="width:100%; padding:8px;">
        </div>
    </div>

    <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
        <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar Estudiante</button>
    </div>
</form>
