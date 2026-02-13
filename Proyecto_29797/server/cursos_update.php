<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recibir y limpiar datos
    $id = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
    $nom = trim($_POST['nombre_curso']);
    $des = trim($_POST['descripcion']);
    $fec = $_POST['fecha_inicio'];
    $dur = intval($_POST['duracion']);

    // 2. Validaciones básicas
    if ($id <= 0 || empty($nom)) {
        die("Error: Datos incompletos o ID no válido.");
    }

    // 3. Preparar la actualización
    $stmt = $conn->prepare("UPDATE cursos SET nombre_curso = ?, descripcion = ?, fecha_inicio = ?, duracion = ? WHERE id_curso = ?");
    $stmt->bind_param("sssii", $nom, $des, $fec, $dur, $id);
    
    // 4. Ejecutar y responder
    if ($stmt->execute()) {
        // Registrar en auditoría si la función existe
        if (function_exists('registrarEvento')) {
            registrarEvento($conn, "Editó curso ID: $id ($nom)");
        }
        echo "Curso actualizado correctamente.";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>