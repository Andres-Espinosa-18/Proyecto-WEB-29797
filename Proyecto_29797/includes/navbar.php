<?php
require_once 'server/db.php';
$user_id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

// Consulta simplificada para la nueva estructura (IDs 1, 2, 3...)
$sql = "SELECT DISTINCT m.* FROM menus m
        WHERE (m.id_menu IN (
            SELECT pr.id_menu FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id
        ) OR m.id_menu IN (
            SELECT m2.parent_id FROM menus m2
            JOIN permisos_rol pr ON m2.id_menu = pr.id_menu
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id
        ))
        AND m.parent_id IS NULL 
        ORDER BY m.id_menu ASC";

$res = $conn->query($sql);
$menu_data = [];
if ($res) {
    while($row = $res->fetch_assoc()) { $menu_data[] = $row; }
}
?>

<nav class="navbar">
    <ul class="menu-list">
        <li><a href="#" class="nav-link" data-view="principal.php">Inicio</a></li>
        <?php foreach ($menu_data as $m): ?>
            <?php if ($m['url'] !== 'principal.php'): // Evitamos duplicar Inicio ?>
                <li>
                    <a href="#" class="nav-link" data-view="<?php echo $m['url']; ?>">
                        <?php echo $m['nombre_texto']; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

    <div class="user-dropdown">
        <div class="user-trigger">
            <strong><?php echo $_SESSION['username']; ?></strong>
            <?php if(isset($_SESSION['user_log'])): ?>
                <small style="display: block; font-size: 0.7rem; color: #bdc3c7;">
                    Sesión: <?php echo $_SESSION['user_log']; ?>
                </small>
            <?php endif; ?>
        </div>
        <ul class="user-menu">
            <li><a href="server/logout.php" class="logout-link">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>