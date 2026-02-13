<?php
require_once '../server/db.php';
$id = intval($_GET['id']);
$c = $conn->query("SELECT * FROM cursos WHERE id_curso=$id")->fetch_assoc();
?>
<div>
    <h3>Editar Curso</h3>
    <form id="form-curso-edit">
        <input type="hidden" name="id_curso" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Nombre Curso:</label>
            <input type="text" name="nombre_curso" class="form-control" value="<?php echo $c['nombre_curso']; ?>" required>
        </div>

        <div class="form-group">
            <label>Descripci&oacute;n:</label>
            <input type="text" name="descripcion" class="form-control" value="<?php echo $c['descripcion']; ?>">
        </div>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
            <div class="form-group">
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $c['fecha_inicio']; ?>">
            </div>
            <div class="form-group">
                <label>Horas:</label>
                <input type="number" name="duracion" class="form-control" value="<?php echo $c['duracion_horas']; ?>">
            </div>
        </div>

        <div style="text-align:right; margin-top:15px;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" onclick="updCurso()">Actualizar</button>
        </div>
    </form>
</div>