<?php
function registrarEvento($conn, $accion) {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    
    $id_usuario = $_SESSION['id_usuario'] ?? 0;
    $nombre_user = $_SESSION['username'] ?? 'Desconocido';
    $ip = $_SERVER['REMOTE_ADDR']; // Captura la IP de la ASUS o red local

    $stmt = $conn->prepare("INSERT INTO auditoria (id_usuario, usuario_nombre, accion, ip_conexion) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $id_usuario, $nombre_user, $accion, $ip);
    $stmt->execute();
}
?>