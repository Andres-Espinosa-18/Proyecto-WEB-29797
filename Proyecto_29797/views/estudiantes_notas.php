<?php
require_once '../server/db.php';
$id_est = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Obtener datos del estudiante
$est = $conn->query("SELECT nombre, apellido, cedula FROM estudiantes WHERE id_estudiante=$id_est")->fetch_assoc();
?>

<div class="contenedor" style="margin-top: 10px;">
    
    <div class="header-complex">
        <div class="header-left">
            <h2 style="color: var(--primary);">Calificaciones: <?php echo htmlspecialchars($est['apellido'] . " " . $est['nombre']); ?></h2>
            <p style="color: var(--text-muted); margin:0;">Cédula: <?php echo $est['cedula']; ?></p>
        </div>
        <div class="header-right">
            <button class="btn btn-danger" onclick="cargarVista('estudiantes.php')">Volver a Estudiantes</button>
        </div>
    </div>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>Curso</th>
                <th class="text-center" width="80">Nota 1</th>
                <th class="text-center" width="80">Nota 2</th>
                <th class="text-center" width="80">Nota 3</th>
                <th class="text-center" width="80">Prom.</th>
                <th class="text-center" width="100">Recuperación</th> 
                <th class="text-center">Estado</th>
                <th class="acciones-col" width="100">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // MODIFICACIÓN SQL: Agregamos n.recuperacion a la consulta
            $sql = "SELECT c.nombre_curso, n.id_nota, n.nota1, n.nota2, n.nota3, n.promedio, n.estado_aprobacion, n.recuperacion 
                    FROM notas n
                    INNER JOIN cursos c ON n.id_curso = c.id_curso
                    WHERE n.id_usuario = $id_est AND n.tipo_usuario = 'estudiante'";
            
            $res = $conn->query($sql);

            if($res && $res->num_rows > 0) {
                while($row = $res->fetch_assoc()) {
                    $idNota = $row['id_nota'];
                    $promedio = floatval($row['promedio']);
                    $recuperacion = floatval($row['recuperacion']);
                    
                    // Colores según estado
                    $color = ($row['estado_aprobacion'] == 'Aprobado') ? 'var(--success)' : 'var(--danger)';
                    if($row['estado_aprobacion'] == 'En Proceso') $color = 'orange';

                    // LÓGICA DE RECUPERACIÓN:
                    // Mostramos el input si está reprobado (promedio < 14) O si ya tiene una nota de recuperación guardada
                    $mostrarRecuperacion = ($promedio < 14 || $recuperacion > 0);

                    echo "<tr>
                            <td style='font-weight:bold;'>" . htmlspecialchars($row['nombre_curso']) . "</td>
                            
                            <td class='text-center'>
                                <input type='number' step='0.01' min='0' max='20' id='n1_$idNota' 
                                       value='" . $row['nota1'] . "' 
                                       style='width:60px; text-align:center; border:1px solid #ccc; border-radius:4px;'>
                            </td>
                            <td class='text-center'>
                                <input type='number' step='0.01' min='0' max='20' id='n2_$idNota' 
                                       value='" . $row['nota2'] . "' 
                                       style='width:60px; text-align:center; border:1px solid #ccc; border-radius:4px;'>
                            </td>
                            <td class='text-center'>
                                <input type='number' step='0.01' min='0' max='20' id='n3_$idNota' 
                                       value='" . $row['nota3'] . "' 
                                       style='width:60px; text-align:center; border:1px solid #ccc; border-radius:4px;'>
                            </td>

                            <td class='text-center' style='font-weight:bold; background:#f9f9f9;'>
                                " . $row['promedio'] . "
                            </td>
                            
                            <td class='text-center'>";
                                if ($mostrarRecuperacion) {
                                    echo "<input type='number' step='0.01' min='0' max='20' id='rec_$idNota' 
                                           value='" . ($recuperacion > 0 ? $recuperacion : '') . "' 
                                           placeholder='Nota'
                                           style='width:60px; text-align:center; border:1px solid #e53e3e; border-radius:4px;'>";
                                } else {
                                    echo "<span style='color:#ccc;'>--</span>";
                                    // Input oculto en 0 para evitar errores en JS si no existe el visible
                                    echo "<input type='hidden' id='rec_$idNota' value='0'>";
                                }
                    echo "  </td>

                            <td class='text-center'>
                                <span style='color:$color; font-weight:bold; font-size:0.85rem;'>
                                    " . $row['estado_aprobacion'] . "
                                </span>
                            </td>

                            <td class='acciones-col'>
                                <button class='btn btn-success' onclick='guardarNota($idNota, $id_est)'>
                                    &#128190; Guardar
                                </button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='8' class='text-center' style='padding:20px;'>El estudiante no tiene cursos inscritos.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function guardarNota(idNota, idEstudiante) {
    // Obtenemos los valores de los inputs normales
    const n1 = document.getElementById('n1_' + idNota).value;
    const n2 = document.getElementById('n2_' + idNota).value;
    const n3 = document.getElementById('n3_' + idNota).value;
    
    // Obtenemos recuperación (si el input visible no existe, tomamos el oculto o 0)
    const inputRec = document.getElementById('rec_' + idNota);
    const rec = inputRec ? inputRec.value : 0;

    const data = new FormData();
    data.append('id_nota', idNota);
    data.append('nota1', n1);
    data.append('nota2', n2);
    data.append('nota3', n3);
    data.append('recuperacion', rec); // Enviamos el nuevo dato

    fetch('server/notas_update.php', {
        method: 'POST',
        body: data
    })
    .then(r => r.text())
    .then(resp => {
        if(resp.trim() == 'ok') {
            alert("Nota actualizada correctamente");
            // Recargamos la misma vista para ver el nuevo promedio y estado
            cargarVista('estudiantes_notas.php?id=' + idEstudiante);
        } else {
            alert("Error al guardar: " + resp);
        }
    });
}
</script>