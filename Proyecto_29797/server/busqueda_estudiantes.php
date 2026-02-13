<?php
require_once 'db.php';

$criterio = $_GET['criterio'] ?? 'nombre';
$term = $_GET['term'] ?? '';
$limite = intval($_GET['limite'] ?? 10);

// Construir consulta
$sql = "SELECT * FROM estudiantes WHERE 1=1 ";

if (!empty($term)) {
    if ($criterio == 'nombre') {
        $sql .= " AND (nombre LIKE '%$term%' OR apellido LIKE '%$term%')";
    } elseif ($criterio == 'cedula') {
        $sql .= " AND cedula LIKE '%$term%'";
    }
}

$sql .= " ORDER BY apellido ASC LIMIT $limite";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
   while ($row = $res->fetch_assoc()) {
    $id = $row['id_estudiante'];
    $estado = $row['estado']; // 1: Activo, 0: Inactivo

    if ($estado == 1) {
        $btnAccion = "<button class='btn btn-danger' onclick='eliminarEstudiante($id)'> &#10006; Eliminar</button>";
    } else {
        $btnAccion = "<button class='btn btn-success' onclick='activarEstudiante($id)'> &#10004; Activar</button>";
    }

    echo "<tr $estiloFila>
            <td>" . htmlspecialchars($row['apellido'] . " " . $row['nombre']) . "</td>
            <td class='text-center'>" . htmlspecialchars($row['cedula']) . "</td>
            <td>" . htmlspecialchars($row['usuario']) . "</td>
            <td>" . htmlspecialchars($row['correo']) . "</td>
            <td class='text-center'>" . ($estado == 1 ? 'Activo' : 'Inactivo') . "</td>
            <td class='acciones-col'>
                <button class='btn btn-alumnos' onclick='verNotasAdmin($id)'> &#128221; Notas</button>
                <button class='btn btn-change' onclick='editarEstudiante($id)'>&#9998; Editar</button>
                $btnAccion
            </td>
          </tr>";
}
} else {
    echo "<tr><td colspan='6' class='text-center'>No se encontraron estudiantes.</td></tr>";
}
?>