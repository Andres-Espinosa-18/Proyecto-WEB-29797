<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
session_start();

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';

if ($id > 0) {
    // --- USUARIO ---
    if ($tipo === 'usuario') {
        $stmt_info = $conn->prepare("SELECT username FROM usuarios WHERE id_usuario = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $u_info = $stmt_info->get_result()->fetch_assoc();
        $nombre_afectado = $u_info['username'] ?? "ID $id";

        if($conn->query("UPDATE usuarios SET estado = 1 WHERE id_usuario = $id")){
            registrarEvento($conn, "Activó al usuario: " . $nombre_afectado);
            echo "ok";
        }

    // --- CURSO ---
    } else if ($tipo === 'curso') {
        $stmt_info = $conn->prepare("SELECT nombre_curso FROM cursos WHERE id_curso = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $c_info = $stmt_info->get_result()->fetch_assoc();
        $curso_afectado = $c_info['nombre_curso'] ?? "ID $id";

        if($conn->query("UPDATE cursos SET estado = 1 WHERE id_curso = $id")){
            registrarEvento($conn, "Activó el curso: " . $curso_afectado);
            echo "ok";
        }

    // --- ESTUDIANTE (NUEVO BLOQUE) ---
    } else if ($tipo === 'estudiante') {
        $stmt_info = $conn->prepare("SELECT nombre, apellido FROM estudiantes WHERE id_estudiante = ?");
        $stmt_info->bind_param("i", $id);
        $stmt_info->execute();
        $e_info = $stmt_info->get_result()->fetch_assoc();
        $est_nombre = ($e_info) ? $e_info['apellido'] . " " . $e_info['nombre'] : "ID $id";

        if($conn->query("UPDATE estudiantes SET estado = 1 WHERE id_estudiante = $id")){
            registrarEvento($conn, "Activó al estudiante: " . $est_nombre);
            echo "ok";
        }
    }
} else {
    echo "Error: ID no válido.";
}
?>