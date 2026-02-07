<?php
// server/actualizar_permisos.php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_rol = intval($_POST['id_rol']);
    $menu_ids = isset($_POST['menu_ids']) ? $_POST['menu_ids'] : [];

    if ($id_rol > 0) {
        // 1. Obtener nombre del rol para la auditoría antes de cualquier cambio
        $res_rol = $conn->query("SELECT nombre_rol FROM roles WHERE id_rol = $id_rol");
        $rol_info = $res_rol->fetch_assoc();
        $nombre_rol = $rol_info['nombre_rol'] ?? "ID $id_rol";

        // 2. Iniciar transacción para evitar que la tabla quede vacía si algo falla
        $conn->begin_transaction();

        try {
            // Borrar permisos anteriores del rol
            $conn->query("DELETE FROM permisos_rol WHERE id_rol = $id_rol");

            // Insertar nuevos permisos
            if (!empty($menu_ids)) {
                $stmt = $conn->prepare("INSERT INTO permisos_rol (id_rol, id_menu) VALUES (?, ?)");
                foreach ($menu_ids as $m_id) {
                    $m_id_int = intval($m_id);
                    $stmt->bind_param("ii", $id_rol, $m_id_int);
                    $stmt->execute();
                }
            }

            // 3. REGISTRO EN AUDITORÍA
            registrarEvento($conn, "Actualizó los permisos del rol: " . $nombre_rol);

            $conn->commit();
            echo "EXITO";
        } catch (Exception $e) {
            $conn->rollback();
            echo "Error al actualizar: " . $e->getMessage();
        }
    } else {
        echo "ID de rol no válido.";
    }
}
?>