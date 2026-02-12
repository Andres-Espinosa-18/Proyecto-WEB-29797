<?php
require_once '../server/db.php';
$id_curso = intval($_GET['id'] ?? 0);

// Info del curso
$res_c = $conn->query("SELECT * FROM cursos WHERE id_curso = $id_curso");
$curso = $res_c->fetch_assoc();

if (!$curso) { echo "<div class='alert alert-danger'>Curso no encontrado.</div>"; exit; }
?>

<div class="contenedor">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h3>Alumnos en: <?php echo htmlspecialchars($curso['nombre_curso']); ?></h3>
        <button class="btn-back" onclick="cargarVista('cursos.php')">⬅ Volver a Cursos</button>
    </div>

    <div style="background:#f8fafc; padding:15px; border:1px solid #ddd; margin-bottom:20px; border-radius:5px;">
        <h4>Inscribir Estudiante Manualmente</h4>
        <div style="display:flex; gap:10px;">
            <select id="selectEstudiante" style="padding:8px; flex:1;">
                <option value="">-- Selecciona un estudiante --</option>
                <?php
                // Mostrar solo estudiantes que NO estén ya en este curso
                $sql_dispo = "SELECT id_estudiante, nombre, cedula FROM estudiantes 
                              WHERE id_estudiante NOT IN (
                                  SELECT id_usuario FROM notas 
                                  WHERE id_curso = $id_curso AND tipo_usuario = 'estudiante'
                              ) AND estado = 1 ORDER BY nombre ASC";
                $res_d = $conn->query($sql_dispo);
                while($d = $res_d->fetch_assoc()) {
                    echo "<option value='{$d['id_estudiante']}'>{$d['nombre']} ({$d['cedula']})</option>";
                }
                ?>
            </select>
            <button class="btn-success" onclick="agregarEstudianteCurso(<?php echo $id_curso; ?>)">➕ Agregar</button>
        </div>
    </div>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Cédula</th>
                <th>Promedio Actual</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Obtener estudiantes inscritos en este curso
            $sql_ins = "SELECT n.id_nota, e.nombre, e.cedula, n.promedio 
                        FROM notas n
                        JOIN estudiantes e ON n.id_usuario = e.id_estudiante
                        WHERE n.id_curso = $id_curso AND n.tipo_usuario = 'estudiante'
                        ORDER BY e.nombre ASC";
            $res_ins = $conn->query($sql_ins);
            
            if ($res_ins && $res_ins->num_rows > 0) {
                while($row = $res_ins->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo $row['cedula']; ?></td>
                <td><?php echo $row['promedio']; ?></td>
                <td>
                    <button class="btn-danger" onclick="quitarEstudiante(<?php echo $row['id_nota']; ?>)">
                        🚫 Quitar del Curso
                    </button>
                </td>
            </tr>
            <?php 
                endwhile;
            } else {
                echo "<tr><td colspan='4'>No hay estudiantes inscritos en este curso.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function agregarEstudianteCurso(idCurso) {
    const idEst = document.getElementById('selectEstudiante').value;
    if(!idEst) { alert("Selecciona un estudiante primero."); return; }

    if(confirm("¿Inscribir a este estudiante?")) {
        const d = new FormData();
        d.append('accion', 'inscribir_admin');
        d.append('id_curso', idCurso);
        d.append('id_estudiante', idEst);

        fetch('server/curso_gestionar_alumno.php', { method: 'POST', body: d })
        .then(r => r.text())
        .then(res => {
            alert(res);
            cargarVista('cursos_ver.php?id=' + idCurso); // Recargar
        });
    }
}

function quitarEstudiante(idNota) {
    if(confirm("¿Seguro que deseas eliminar a este estudiante del curso? Se borrarán sus notas asociadas.")) {
        const d = new FormData();
        d.append('accion', 'eliminar_inscripcion');
        d.append('id_nota', idNota);

        fetch('server/curso_gestionar_alumno.php', { method: 'POST', body: d })
        .then(r => r.text())
        .then(res => {
            alert(res);
            // Recargamos la misma vista para ver cambios
            const urlParams = new URLSearchParams(window.location.search); // Truco para obtener ID actual si se perdiera
            // Mejor usamos la variable PHP que ya imprimimos en el onclick anterior o recargamos el div
            // Simplemente volvemos a llamar a cargarVista con el ID que ya tenemos en PHP
            cargarVista('cursos_ver.php?id=<?php echo $id_curso; ?>'); 
        });
    }
}
</script>