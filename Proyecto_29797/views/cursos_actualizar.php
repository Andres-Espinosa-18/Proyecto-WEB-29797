<?php
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM cursos WHERE id_curso = $id");
$c = $res->fetch_assoc();
?>

<div class="contenedor">
    <h3>Editar Curso: <?php echo htmlspecialchars($c['nombre_curso']); ?></h3>
    <form id="form-curso-editar">
        <input type="hidden" name="id_curso" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Nombre del Curso:</label>
            <input type="text" name="nombre_curso" value="<?php echo htmlspecialchars($c['nombre_curso']); ?>" required>
        </div>

        <div class="form-group">
            <label>Descripción:</label>
            <input type="text" name="descripcion" value="<?php echo htmlspecialchars($c['descripcion']); ?>">
        </div>

        <div class="form-group" style="display: flex; gap: 20px;">
            <div style="flex: 1;">
                <label>Fecha de Inicio:</label>
                <input type="date" name="fecha_inicio" value="<?php echo $c['fecha_inicio']; ?>" required>
            </div>
            <div style="flex: 1;">
                <label>Duración (Horas):</label>
                <input type="text" name="duracion" value="<?php echo $c['duracion_horas']; ?>">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="button" onclick="actualizarCurso()" class="btn-success">Guardar Cambios</button>
            <button type="button" onclick="cargarVista('cursos.php')" class="btn-back">Cancelar</button>
        </div>
    </form>
</div>

<script>
window.actualizarCurso = function() {
    const form = document.getElementById('form-curso-editar');
    const datos = new FormData(form);
    datos.append('accion', 'actualizar');

    fetch('server/cursos_acciones.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(data => {
        alert(data);
        cargarVista('cursos.php');
    });
}
</script>