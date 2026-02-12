<?php
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// --- SEGURIDAD: SOLO ESTUDIANTES PUEDEN INSCRIBIRSE ---
// Si el rol del sistema NO es 'estudiante' (es decir, es 'administrativo'), bloqueamos.
if (!isset($_SESSION['rol_sistema']) || $_SESSION['rol_sistema'] !== 'estudiante') {
    ?>
    <div class="contenedor">
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 20px; border-radius: 5px; border: 1px solid #f5c6cb;">
            <h3>🚫 Acción no permitida</h3>
            <p>Como usuario administrativo, <b>no puedes inscribirte a cursos</b> personalmente.</p>
            <p>Para gestionar alumnos en cursos, ve al menú <b>Cursos</b> y selecciona el botón <b>"👥 Alumnos"</b>.</p>
        </div>
    </div>
    <?php
    exit(); // Detenemos la ejecución aquí.
}
// -------------------------------------------------------

$user_id = $_SESSION['id_usuario'];
?>

<div class="contenedor">
    <h2>Cursos Disponibles para Inscripción</h2>
    <div class="grid-cursos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <?php
        // Mostrar cursos donde el estudiante NO esté inscrito
        $sql = "SELECT * FROM cursos WHERE id_curso NOT IN (
            SELECT id_curso FROM notas 
            WHERE id_usuario = $user_id AND tipo_usuario = 'estudiante'
        ) AND estado = 1";
        
        $res = $conn->query($sql);
        
        if ($res && $res->num_rows > 0):
            while($c = $res->fetch_assoc()):
        ?>
        <div class="card-curso" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <h3 style="color:#2c3e50;"><?php echo htmlspecialchars($c['nombre_curso']); ?></h3>
            <p style="color:#7f8c8d; font-size:0.9em;"><?php echo htmlspecialchars($c['descripcion']); ?></p>
            <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
            <p><strong>Inicio:</strong> <?php echo $c['fecha_inicio']; ?></p>
            <p><strong>Duración:</strong> <?php echo $c['duracion_horas']; ?> horas</p>
            <button class="btn-success" style="width:100%; margin-top:10px;" onclick="inscribirse(<?php echo $c['id_curso']; ?>)">Inscribirme Ahora</button>
        </div>
        <?php 
            endwhile;
        else:
        ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 20px; color: #7f8c8d;">
                <p>No hay cursos disponibles o ya estás inscrito en todos.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function inscribirse(idCurso) {
    if(confirm("¿Deseas inscribirte en este curso?")) {
        const d = new FormData();
        d.append('id_curso', idCurso);
        
        fetch('server/curso_inscribir.php', { method: 'POST', body: d })
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            cargarVista('calificaciones.php'); 
        });
    }
}
</script>