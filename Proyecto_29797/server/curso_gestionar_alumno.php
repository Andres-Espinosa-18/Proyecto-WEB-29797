<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
session_start();

// Solo Admin puede hacer esto (Check rápido)
if (!isset($_SESSION['rol_sistema']) || $_SESSION['rol_sistema'] !== 'administrativo') {
    die("Acceso denegado.");
}

$accion = $_POST['accion'] ?? '';

if ($accion === 'inscribir_admin') {
    $id_c = intval($_POST['id_curso']);
    $id_e = intval($_POST['id_estudiante']);

    // Inscribir (Crear registro en notas vacío)
    $stmt = $conn->prepare("INSERT INTO notas (id_usuario, id_curso, estado_aprobacion, tipo_usuario) VALUES (?, ?, 'En Proceso', 'estudiante')");
    $stmt->bind_param("ii", $id_e, $id_c);

    if ($stmt->execute()) {
        registrarEvento($conn, "Admin inscribió al estudiante ID $id_e en curso ID $id_c");
        echo "Estudiante inscrito correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }

} elseif ($accion === 'eliminar_inscripcion') {
    $id_nota = intval($_POST['id_nota']);

    // Obtenemos datos para auditoría antes de borrar
    $q = $conn->query("SELECT id_usuario, id_curso FROM notas WHERE id_nota = $id_nota");
    $info = $q->fetch_assoc();

    $stmt = $conn->prepare("DELETE FROM notas WHERE id_nota = ?");
    $stmt->bind_param("i", $id_nota);

    if ($stmt->execute()) {
        registrarEvento($conn, "Admin eliminó inscripción (ID Nota: $id_nota)");
        echo "Estudiante eliminado del curso.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>