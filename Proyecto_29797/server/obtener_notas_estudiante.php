<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$id_sesion = isset($_SESSION['id_usuario']) ? intval($_SESSION['id_usuario']) : 0;
$rol = isset($_SESSION['rol_sistema']) ? $_SESSION['rol_sistema'] : '';

if ($id_sesion <= 0 || $rol !== 'estudiante') {
    echo "<tr><td colspan='7' style='text-align:center;'>Error de sesión.</td></tr>";
    exit;
}

// Consulta con todos los campos de notas según tu SQL
$sql = "SELECT c.nombre_curso, n.nota1, n.nota2, n.nota3, n.recuperacion, n.promedio, n.estado_aprobacion 
        FROM notas n 
        INNER JOIN cursos c ON n.id_curso = c.id_curso 
        WHERE n.id_usuario = $id_sesion 
        AND n.tipo_usuario = 'estudiante'
        ORDER BY c.nombre_curso ASC";

$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $estado = $row['estado_aprobacion'];
        $color = "#7f8c8d"; // En Proceso
        if ($estado == 'Aprobado') $color = "#27ae60";
        if ($estado == 'Reprobado') $color = "#c0392b";

        // Manejo de valor nulo en recuperación
        $recu = ($row['recuperacion'] !== null) ? number_format($row['recuperacion'], 2) : '-';

        echo "<tr>
                <td style='font-weight:bold;'>" . htmlspecialchars($row['nombre_curso']) . "</td>
                <td style='text-align:center;'>" . number_format($row['nota1'], 2) . "</td>
                <td style='text-align:center;'>" . number_format($row['nota2'], 2) . "</td>
                <td style='text-align:center;'>" . number_format($row['nota3'], 2) . "</td>
                <td style='text-align:center; color: #7f8c8d;'>$recu</td>
                <td style='text-align:center; font-weight:bold; background-color: #f9f9f9;'>" . number_format($row['promedio'], 2) . "</td>
                <td style='text-align:center;'>
                    <span style='background: $color; color: white; padding: 3px 10px; border-radius: 10px; font-size: 0.75rem;'>
                        " . strtoupper($estado) . "
                    </span>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7' style='text-align:center; padding:20px;'>No tienes notas registradas aún.</td></tr>";
}
?>