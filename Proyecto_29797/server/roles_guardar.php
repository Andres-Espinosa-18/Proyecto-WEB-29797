<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_rol']);
    $desc = trim($_POST['descripcion']);
    
    $conn->query("INSERT INTO roles (nombre_rol, descripcion) VALUES ('$nombre', '$desc')");
    
    if(function_exists('registrarEvento')) registrarEvento($conn, "Creó rol: $nombre");
    echo "Rol creado correctamente.";
}
?>