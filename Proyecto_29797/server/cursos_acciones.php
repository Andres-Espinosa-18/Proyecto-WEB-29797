<?php
require_once 'db.php';
require_once 'funciones_auditoria.php'; // Para registrar quien hizo qué
session_start();

$accion = $_POST['accion'] ?? '';
$id_usuario = $_SESSION['id_usuario'] ?? 0;

if ($accion == 'crear') {
    $nombre = $_POST['nombre_curso'];
    $desc = $_POST['descripcion'];
    $fecha = $_POST['fecha_inicio'];
    $horas = intval($_POST['duracion']);

    $stmt = $conn->prepare("INSERT INTO cursos (nombre_curso, descripcion, fecha_inicio, duracion_horas) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nombre, $desc, $fecha, $horas);
    
    if ($stmt->execute()) {
        registrarEvento($conn, "Creó el curso: $nombre");
        echo "Curso creado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }

} elseif ($accion == 'actualizar') {
    $id = intval($_POST['id_curso']);
    $nombre = $_POST['nombre_curso'];
    $desc = $_POST['descripcion'];
    $fecha = $_POST['fecha_inicio'];
    $horas = intval($_POST['duracion']);

    $stmt = $conn->prepare("UPDATE cursos SET nombre_curso=?, descripcion=?, fecha_inicio=?, duracion_horas=? WHERE id_curso=?");
    $stmt->bind_param("sssii", $nombre, $desc, $fecha, $horas, $id);

    if ($stmt->execute()) {
        registrarEvento($conn, "Actualizó el curso ID: $id");
        echo "Curso actualizado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>