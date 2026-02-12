<?php
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM estudiantes WHERE id_estudiante = $id");
$e = $res->fetch_assoc();

if(!$e) { echo "Estudiante no encontrado"; exit; }
?>

<div class="contenedor">
    <h3>Editar Estudiante: <?php echo htmlspecialchars($e['nombre']); ?></h3>
    <form id="form-est-editar">
        <input type="hidden" name="id_estudiante" value="<?php echo $id; ?>">
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            <div>
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($e['nombre']); ?>" required>
            </div>
            
            <div>
                <label>Cédula:</label>
                <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($e['cedula']); ?>" required>
            </div>

            <div>
                <label>Usuario (Login):</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($e['usuario']); ?>" disabled style="background:#eee;">
            </div>

            <div>
                <label>Nueva Contraseña (Dejar vacío para mantener):</label>
                <input type="password" name="password" class="form-control" placeholder="********">
            </div>

            <div>
                <label>Carrera:</label>
                <input type="text" name="carrera" class="form-control" value="<?php echo htmlspecialchars($e['carrera']); ?>">
            </div>

            <div>
                <label>Fecha Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" class="form-control" value="<?php echo $e['fecha_nacimiento']; ?>">
            </div>

            <div>
                <label>Correo:</label>
                <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($e['correo']); ?>">
            </div>

            <div>
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($e['telefono']); ?>">
            </div>

            <div style="grid-column: 1 / -1;">
                <label>Dirección:</label>
                <input type="text" name="direccion" class="form-control" value="<?php echo htmlspecialchars($e['direccion']); ?>">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="button" onclick="editarEstudiante()" class="btn-success">Guardar Cambios</button>
            <button type="button" onclick="cargarVista('estudiantes.php')" class="btn-back">Cancelar</button>
        </div>
    </form>
</div>

<style>
    .form-control { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
</style>

<script>
function editarEstudiante() {
    const form = document.getElementById('form-est-editar');
    const datos = new FormData(form);
    datos.append('accion', 'editar');

    fetch('server/estudiantes_acciones.php', { method: 'POST', body: datos })
    .then(r => r.text())
    .then(msg => {
        alert(msg);
        if(msg.includes('correctamente')) cargarVista('estudiantes.php');
    });
}
</script>