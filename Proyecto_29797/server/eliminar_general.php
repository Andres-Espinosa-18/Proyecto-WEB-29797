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

        $conn->query("UPDATE usuarios SET estado = 'I' WHERE id_usuario = $id");
        
        // REGISTRO EN AUDITORÍA
        registrarEvento($conn, "Inactivó al usuario: " . $nombre_afectado);
        echo "Usuario inactivado con éxito.";
    } 
    elseif ($tipo === 'rol') {
        // Obtenemos el nombre del rol antes de borrarlo
        $stmt_info = $conn->prepare("SELECT nombre_rol FROM roles WHERE id_rol = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $res_info = $stmt_info->get_result();
        $r_info = $res_info->fetch_assoc();
        $nombre_rol = $r_info['nombre_rol'] ?? "ID $id";

        // Primero limpiamos los permisos para evitar errores de integridad
        $conn->query("DELETE FROM permisos_rol WHERE id_rol = $id");
        $query_del = $conn->query("DELETE FROM roles WHERE id_rol = $id");

        if ($query_del) {
            // REGISTRO EN AUDITORÍA
            registrarEvento($conn, "Eliminó el rol: " . $nombre_rol);
            echo "Rol eliminado con éxito.";
        } else {
            echo "Error al eliminar: " . $conn->error;
        }
    }
} else {
    echo "Error: ID no válido.";
}
?>