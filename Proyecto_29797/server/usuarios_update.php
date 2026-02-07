<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_real']);
    $estado = $_POST['estado'];

    if ($id > 0 && !empty($nombre) && $id_rol > 0) {
        
        $conn->begin_transaction();

        try {
            // 1. Actualizar datos básicos
            $stmt = $conn->prepare("UPDATE usuarios SET nombre_real = ?, estado = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssi", $nombre, $estado, $id);
            $stmt->execute();


            // 4. Auditoría
            registrarEvento($conn, "Actualizó al usuario ID $id. Nuevo nombre: $nombre, Estado: $estado");

            $conn->commit();
            echo "Usuario y Rol actualizados con éxito.";

        } catch (Exception $e) {
            $conn->rollback();
            echo "Error al actualizar: " . $e->getMessage();
        }
    } else {
        echo "Error: Datos incompletos.";
    }
}
?>