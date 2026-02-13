<?php
require_once 'db.php';
session_start();

$id = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
$rol = isset($_POST['rol_actual']) ? $_POST['rol_actual'] : '';
$correo = isset($_POST['correo']) ? $conn->real_escape_string($_POST['correo']) : '';
$clave = isset($_POST['clave1']) ? $_POST['clave1'] : '';

if ($id > 0) {
    // 1. Definir tabla y campos según rol
    if ($rol === 'estudiante') {
        $sql = "UPDATE estudiantes SET correo = '$correo'";
        if (!empty($clave)) $sql .= ", password = '$clave'"; // Campo 'password' en estudiantes
        
        // Aquí ajusta la condición WHERE según cómo relacionas tu sesión
        // Si id_usuario es la PK de estudiantes:
        $sql .= " WHERE id_usuario = $id"; 
        // O si usas id_estudiante, deberás haberlo pasado en el form.
        // Asumiremos enlace por id_usuario que es lo común en Login unificado.
        
    } else {
        $sql = "UPDATE usuarios SET email = '$correo'"; // Campo 'email' en usuarios
        if (!empty($clave)) $sql .= ", password = '$clave'";
        $sql .= " WHERE id_usuario = $id";
    }

    // 2. Ejecutar
    if ($conn->query($sql)) {
        echo "ok";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "ID no válido.";
}
?>