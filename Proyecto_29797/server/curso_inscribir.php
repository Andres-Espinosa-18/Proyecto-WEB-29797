<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_u = $_SESSION['id_usuario'];
    $id_c = intval($_POST['id_curso']);
    
    // Determinamos el tipo para guardarlo en la BD
    $rol = $_SESSION['rol_sistema'] ?? 'usuario';
    $tipo_usuario = ($rol === 'estudiante') ? 'estudiante' : 'usuario';

    // Insertamos incluyendo el tipo_usuario
    $stmt = $conn->prepare("INSERT INTO notas (id_usuario, id_curso, estado_aprobacion, tipo_usuario) VALUES (?, ?, 'En Proceso', ?)");
    $stmt->bind_param("iis", $id_u, $id_c, $tipo_usuario);

    if ($stmt->execute()) {
        registrarEvento($conn, "Se inscribió en el curso ID: $id_c ($tipo_usuario)");
        echo "Inscripción exitosa. ¡Bienvenido al curso!";
    } else {
        echo "Error al inscribirse: " . $conn->error;
    }
}
?>