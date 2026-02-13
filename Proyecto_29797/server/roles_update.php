<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
session_start();

// Usamos null coalescing para asegurar que siempre haya un valor
$id_rol = isset($_POST['id_rol']) ? $_POST['id_rol'] : null;
$nombre = isset($_POST['nombre_rol']) ? $conn->real_escape_string($_POST['nombre_rol']) : '';
$desc   = isset($_POST['descripcion']) ? $conn->real_escape_string($_POST['descripcion']) : '';

// Validamos que el ID no sea nulo (puede ser 0)
if ($id_rol !== null) {
    $id_rol = intval($id_rol);

    // Si el nombre viene en el formulario (no era readonly), se actualiza
    if (!empty($nombre)) {
        $sql = "UPDATE roles SET nombre_rol = '$nombre', descripcion = '$desc' WHERE id_rol = $id_rol";
    } else {
        $sql = "UPDATE roles SET descripcion = '$desc' WHERE id_rol = $id_rol";
    }

    if ($conn->query($sql)) {
        registrarEvento($conn, "Actualizó el rol ID: $id_rol");
        echo "ok";
		
    } else {
        echo "Error SQL: " . $conn->error;
    }
} else {
    echo "Error: No se recibió el ID del rol en el servidor.";
}
?>