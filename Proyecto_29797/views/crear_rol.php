<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../server/db.php';

$user_id = $_SESSION['id_usuario'] ?? 0;

function tienePermisoRol($conn, $user_id, $id_menu) {
    if ($user_id == 0) return false;
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
    $res = $conn->query($sql);
    return ($res->fetch_assoc()['total'] > 0);
}
?>

<div class="contenedor">
        <h2>Gestión de Roles</h2>
        <?php if(tienePermisoRol($conn, $user_id, 13)): ?>
            <button onclick="cargarVista('roles_crear.php')" class="btn-success">+ Nuevo Rol</button>
        <?php endif; ?>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Rol</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM roles ORDER BY id_rol ASC");
            while($r = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $r['id_rol']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['nombre_rol']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
                <td>
                    <?php if(tienePermisoRol($conn, $user_id, 14)): ?>
                        <button title="Editar" class="btn-change" onclick="cargarVista('roles_actualizar.php?id=<?php echo $r['id_rol']; ?>')">✏️ Editar</button>
                    <?php endif; ?>

                    <?php if(tienePermisoRol($conn, $user_id, 15)): ?>
                        <button title="Eliminar" class="btn-danger" onclick="eliminarFila(<?php echo $r['id_rol']; ?>, 'rol')">🗑️ Eliminar</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>