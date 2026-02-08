<?php
require_once 'db.php';
require_once 'funciones_auditoria.php'; // Esencial para que se registre

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($id > 0) {
    if ($tipo === 'usuario') {
        // Obtenemos el nombre antes de borrarlo para la auditoría
        $stmt_info = $conn->prepare("SELECT username FROM usuarios WHERE id_usuario = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $res_info = $stmt_info->get_result();
        $u_info = $res_info->fetch_assoc();
        $nombre_afectado = $u_info['username'] ?? "ID $id";

        $conn->query("UPDATE usuarios SET estado = 1 WHERE id_usuario = $id");
        
        // REGISTRO EN AUDITORÍA
        registrarEvento($conn, "Activó al usuario: " . $nombre_afectado);
        echo "Usuario activado con éxito.";
    } 
} else {
    echo "Error: ID no válido.";
}
?>