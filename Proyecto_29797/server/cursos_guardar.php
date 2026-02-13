<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nombre_curso']);
    $des = trim($_POST['descripcion']);
    $fec = $_POST['fecha_inicio'];
    $dur = intval($_POST['duracion']);

    $stmt = $conn->prepare("INSERT INTO cursos (nombre_curso, descripcion, fecha_inicio, duracion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $nom, $des, $fec, $dur);
    
    if($stmt->execute()) echo "Curso guardado correctamente.";
    else echo "Error: " . $conn->error;
}
?>