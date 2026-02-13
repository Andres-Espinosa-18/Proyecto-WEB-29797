<?php
require_once 'db.php';

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';
$id_curso = isset($_GET['id_curso']) ? intval($_GET['id_curso']) : 0;

// --- 1. LISTAR LOS ALUMNOS INSCRITOS ---
if ($accion === 'listar') {
    // CORRECCIÓN: Seleccionamos datos del ESTUDIANTE, no del curso
    $sql = "SELECT e.nombre, e.apellido, e.cedula, n.id_nota 
            FROM notas n 
            INNER JOIN estudiantes e ON n.id_usuario = e.id_estudiante 
            WHERE n.id_curso = $id_curso 
            AND n.tipo_usuario = 'estudiante'
            ORDER BY e.apellido ASC";

    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['apellido'] . ' ' . $row['nombre']) . "</td>
                    <td class='text-center'>" . htmlspecialchars($row['cedula']) . "</td>
                    <td class='acciones-col'>
                        <button class='btn btn-danger' onclick='eliminarInscripcion(" . $row['id_nota'] . ")'>
                            &times; Retirar
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='3' style='text-align:center; padding:15px;'>No hay alumnos inscritos en este curso.</td></tr>";
    }
}

// --- 2. INSCRIBIR ALUMNO ---
if ($accion === 'inscribir') {
    $id_estudiante = intval($_GET['id_estudiante']);

    // Validación de seguridad: ID Curso no puede ser 0
    if ($id_curso <= 0) {
        echo "Error: ID de curso no válido.";
        exit;
    }
    
    // Verificar duplicados
    $check = $conn->query("SELECT id_nota FROM notas WHERE id_usuario = $id_estudiante AND id_curso = $id_curso AND tipo_usuario = 'estudiante'");
    
    if ($check->num_rows == 0) {
        $sql = "INSERT INTO notas (id_usuario, id_curso, tipo_usuario, estado_aprobacion) 
                VALUES ($id_estudiante, $id_curso, 'estudiante', 'En Proceso')";
        if ($conn->query($sql)) {
            echo "ok";
        } else {
            echo "Error SQL: " . $conn->error;
        }
    } else {
        echo "El alumno ya está inscrito.";
    }
}

// --- 3. ELIMINAR INSCRIPCIÓN ---
if ($accion === 'eliminar') {
    $id_nota = isset($_GET['id_nota']) ? intval($_GET['id_nota']) : 0;
    if ($conn->query("DELETE FROM notas WHERE id_nota = $id_nota")) {
        echo "ok";
    } else {
        echo "Error al eliminar";
    }
}
?>