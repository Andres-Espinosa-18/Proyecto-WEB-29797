<?php
require_once '../server/db.php'; 
$id_curso = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res_curso = $conn->query("SELECT nombre_curso FROM cursos WHERE id_curso = $id_curso");
$nombre_curso = ($res_curso->num_rows > 0) ? $res_curso->fetch_assoc()['nombre_curso'] : 'Curso';

// Estudiantes disponibles para el select
$sql_disponibles = "SELECT id_estudiante, nombre, apellido, cedula 
                    FROM estudiantes 
                    WHERE estado = 1 
                    AND id_estudiante NOT IN (
                        SELECT id_usuario FROM notas WHERE id_curso = $id_curso AND tipo_usuario = 'estudiante'
                    )
                    ORDER BY apellido ASC";
$res_estudiantes = $conn->query($sql_disponibles);
?>

<input type="hidden" id="idCursoActual" value="<?php echo $id_curso; ?>">

<div class="contenedor" style="margin-top: 0; padding-top: 20px;">
    
    <div class="header-complex" style="border-bottom: 2px solid #edf2f7; margin-bottom: 20px;">
        <div class="header-left">
            <h2 style="font-size: 1.5rem; color: var(--primary);">Gestionar: <?php echo htmlspecialchars($nombre_curso); ?></h2>
        </div>
        <div class="header-right">
             <button class="btn btn-danger" onclick="cargarVista('cursos.php')">Volver</button>
        </div>
    </div>

    <div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 30px;">
        <label style="font-weight: bold; color: var(--primary); margin-bottom: 10px; display: block;">
            Seleccionar Alumno para Inscribir:
        </label>
        
        <div style="display: flex; gap: 10px;">
            <select id="selectAlumno" style="flex: 1; padding: 10px; border: 1px solid var(--border); border-radius: 6px;">
                <option value="">-- Seleccione un estudiante --</option>
                <?php 
                if ($res_estudiantes && $res_estudiantes->num_rows > 0) {
                    while($est = $res_estudiantes->fetch_assoc()) {
                        echo "<option value='" . $est['id_estudiante'] . "'>" . 
                             htmlspecialchars($est['apellido'] . " " . $est['nombre'] . " - " . $est['cedula']) . 
                             "</option>";
                    }
                } else {
                    echo "<option value=''>No hay alumnos disponibles</option>";
                }
                ?>
            </select>

            <button class="btn btn-success" onclick="inscribirDesdeSelect()">
                + Inscribir
            </button>
        </div>
    </div>

    <h3 style="color: var(--primary); font-size: 1.1rem; margin-bottom: 15px;">Listado de Inscritos</h3>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>Apellidos y Nombres</th>
                <th class="text-center">Cédula</th>
                <th class="acciones-col">Acción</th>
            </tr>
        </thead>
        <tbody id="tablaInscritos">
            </tbody>
    </table>
    
    <img src="" onerror="cargarTablaInscritos()" style="display:none;">
</div>