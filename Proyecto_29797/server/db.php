<?php
$host = "localhost";
$user = "admin";
$pass = "admin";
$db   = "Proyecto_29797";

$conn = new mysqli($host, $user, $pass, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Set charset para eñes y acentos
$conn->set_charset("utf8");
?>