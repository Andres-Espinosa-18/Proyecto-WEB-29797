<?php
require_once 'db.php';
require_once 'funciones_auditoria.php'; 
session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($id > 0) {
    
    // --- USUARIO (Inactivar) ---
    if ($tipo === 'usuario') {
        $stmt_info = $conn->prepare("SELECT username FROM usuarios WHERE id_usuario = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $u_info = $stmt_info->get_result()->fetch_assoc();
        $nombre_afectado = $u_info['username'] ?? "ID $id";

        if($conn->query("UPDATE usuarios SET estado = 0 WHERE id_usuario = $id")){
            registrarEvento($conn, "Inactivó al usuario: " . $nombre_afectado);
            echo "Usuario inactivado con éxito.";
        } else {
            echo "Error al inactivar.";
        }

    // --- ROL (Eliminar físico con limpieza) ---
    } elseif ($tipo === 'rol') {
        // Validar que no sea admin
        if($id <= 1) { echo "No se puede eliminar roles del sistema."; exit; }

        $stmt_info = $conn->prepare("SELECT nombre_rol FROM roles WHERE id_rol = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $r_info = $stmt_info->get_result()->fetch_assoc();
        $nombre_rol = $r_info['nombre_rol'] ?? "ID $id";
        
        // Mover usuarios al rol 0 (Sin Rol)
        $conn->query("UPDATE usuario_roles SET id_rol = 0 WHERE id_rol = $id");
        
        // Eliminar permisos y el rol
        $conn->query("DELETE FROM permisos_rol WHERE id_rol = $id");
        if ($conn->query("DELETE FROM roles WHERE id_rol = $id")) {
            registrarEvento($conn, "Eliminó el rol: " . $nombre_rol);
            echo "Rol eliminado con éxito.";
        } else {
            echo "Error al eliminar: " . $conn->error;
        }

    // --- CURSO (Inactivar) ---
    } elseif ($tipo === 'curso') {
        if($conn->query("UPDATE cursos SET estado = 0 WHERE id_curso = $id")){
            registrarEvento($conn, "Inactivó el curso ID $id");
            echo "Curso inactivado.";
        }
    } elseif ($tipo === 'estudiante') {
        if($conn->query("UPDATE estudiantes SET estado = 0 WHERE id_estudiante = $id")){
            registrarEvento($conn, "Inactivó al estudiante ID $id");
            echo "ok"; // IMPORTANTE para el JS
        } else {
            echo "Error al inactivar: " . $conn->error;
        }
    }

} else {
    echo "Error: ID no válido.";
}
?>