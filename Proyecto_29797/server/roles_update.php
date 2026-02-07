<?php
require_once 'db.php';
require_once 'funciones_auditoria.php'; // Importamos la función de registro

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id_rol']);
    $nombre = $_POST['nombre_rol'];
    $desc = $_POST['descripcion'];

    // Preparamos la actualización del rol
    $stmt = $conn->prepare("UPDATE roles SET nombre_rol = ?, descripcion = ? WHERE id_rol = ?");
    $stmt->bind_param("ssi", $nombre, $desc, $id);

    if ($stmt->execute()) {
        // REGISTRO EN AUDITORÍA
        // Guardamos quién modificó el rol, qué rol fue y desde qué IP
        registrarEvento($conn, "Actualizó el rol: " . $nombre);
        
        echo "Rol actualizado con éxito.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>