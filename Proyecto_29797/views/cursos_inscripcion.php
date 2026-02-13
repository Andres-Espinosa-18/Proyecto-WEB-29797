<?php 
require_once '../server/db.php'; 
session_start();
$uid = $_SESSION['id_usuario'];
?>
<div class="contenedor">
    <div class="header-optimo">
        <h2>Inscripción de Cursos</h2>
    </div>
    
    <div style="background:white; padding:20px; border-radius:8px; box-shadow:0 2px 5px rgba(0,0,0,0.1); max-width:500px; margin:20px auto;">
        <label style="font-weight:bold;">Seleccione el curso:</label>
        <select id="selCurso" class="form-control" style="padding:10px; margin:15px 0;">
            <option value="">-- Seleccionar --</option>
            <?php
            $sql = "SELECT * FROM cursos WHERE id_curso NOT IN (SELECT id_curso FROM notas WHERE id_usuario = $uid AND tipo_usuario='estudiante') AND estado = 1";
            $r = $conn->query($sql);
            while($c=$r->fetch_assoc()) {
                echo "<option value='{$c['id_curso']}'>{$c['nombre_curso']} (Inicio: {$c['fecha_inicio']})</option>";
            }
            ?>
        </select>
        <button class="btn-success" style="width:100%;" onclick="inscribir()">Confirmar Inscripción</button>
    </div>
</div>
<script>
function inscribir() {
    const id = document.getElementById('selCurso').value;
    if(!id) return alert("Seleccione un curso");
    const d = new FormData(); d.append('id_curso', id);
    fetch('server/curso_inscribir.php', {method:'POST', body:d})
    .then(r=>r.text()).then(m=>{ alert(m); cargarVista('calificaciones.php'); });
}
</script>