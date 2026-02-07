<?php
// 1. Incluimos la base de datos (ruta relativa desde /views hacia /server)
require_once '../server/db.php';

// Iniciamos sesión si no está iniciada (necesaria para el user_id)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['id_usuario'] ?? 0;

// 2. DEFINIMOS LA FUNCIÓN (Fundamental para que no dé error)
function tienePermiso($conn, $user_id, $id_menu) {
    // Si no hay usuario, no tiene permiso
    if ($user_id == 0) return false;
    
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
            
    $res = $conn->query($sql);
    if ($res) {
        $row = $res->fetch_assoc();
        return $row['total'] > 0;
    }
    return false;
}
?>

<div class="contenedor">
    <h2>Gestión de Usuarios</h2>
    
    <?php if(tienePermiso($conn, $user_id, 10)): ?>
        <button onclick="cargarVista('usuarios_crear.php')" class="btn-success">+ Nuevo Usuario</button>
    <?php endif; ?>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>Username</th>
                <th>Nombre del Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM usuarios");
            if ($res):
                while($u = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($u['username']); ?></td>
                <td><?php echo htmlspecialchars($u['nombre_real']); ?></td>
                <td>
                    <?php if(tienePermiso($conn, $user_id, 11)): ?>
                        <button class="btn-change" onclick="cargarVista('usuarios_actualizar.php?id=<?php echo $u['id_usuario']; ?>')">✏️ Editar</button>
                    <?php endif; ?>

                    <?php if(tienePermiso($conn, $user_id, 12)): ?>
                        <button class="btn-danger" onclick="eliminarFila(<?php echo $u['id_usuario']; ?>, 'usuario')">🗑️ Eliminar</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php 
                endwhile; 
            endif;
            ?>
        </tbody>
    </table>
</div>