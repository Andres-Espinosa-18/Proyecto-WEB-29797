<?php
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$user_id = $_SESSION['id_usuario'];
?>

<div class="contenedor">
    <h2>Cursos Disponibles para Inscripción</h2>
    <div class="grid-cursos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;">
        <?php
        // Traer cursos donde el alumno NO esté inscrito todavía
        $sql = "SELECT * FROM cursos WHERE id_curso NOT IN (SELECT id_curso FROM notas WHERE id_usuario = $user_id)";
        $res = $conn->query($sql);
        while($c = $res->fetch_assoc()):
        ?>
        <div class="card-curso" style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; background: white;">
            <h3><?php echo htmlspecialchars($c['nombre_curso']); ?></h3>
            <p><?php echo htmlspecialchars($c['descripcion']); ?></p>
            <p><strong>Inicia:</strong> <?php echo $c['fecha_inicio']; ?></p>
            <button class="btn-success" onclick="inscribirse(<?php echo $c['id_curso']; ?>)">Inscribirme</button>
        </div>
        <?php endwhile; ?>
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
            cargarVista('calificaciones.php'); // Lo mandamos a ver sus notas (en 0)
        });
    }
}
</script>