<?php
require_once 'db.php';

$id = $_POST['id'];

// 1. Verificar si tiene dependencias (ej: si tiene roles asignados)
$check = $conn->query("SELECT COUNT(*) as total FROM usuario_roles WHERE id_usuario = $id");
$tiene_roles = $check->fetch_assoc()['total'] > 0;

if ($tiene_roles) {
    // 2. Soft Delete: Cambiar estado a Inactivo
    $stmt = $conn->prepare("UPDATE usuarios SET estado = 'I' WHERE id_usuario = ?");
} else {
    // 3. Hard Delete: Borrar de la base de datos
    $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
}

$stmt->bind_param("i", $id);
if($stmt->execute()) echo "success";
?>