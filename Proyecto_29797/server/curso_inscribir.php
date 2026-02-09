<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_u = $_SESSION['id_usuario'];
    $id_c = intval($_POST['id_curso']);

    $stmt = $conn->prepare("INSERT INTO notas (id_usuario, id_curso, estado_aprobacion) VALUES (?, ?, 'En Proceso')");
    $stmt->bind_param("ii", $id_u, $id_c);

    if ($stmt->execute()) {
        registrarEvento($conn, "Se inscribió en el curso ID: $id_c");
        echo "Inscripción exitosa. ¡Bienvenido al curso!";
    } else {
        echo "Error al inscribirse.";
    }
}