<?php
// Evitar error si se incluye varias veces
require_once 'server/db.php';

$rol_sistema = isset($_SESSION['rol_sistema']) ? $_SESSION['rol_sistema'] : 'invitado';
$user_id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
$menu_data = [];

// Solo cargamos menú dinámico de BD si es ADMINISTRATIVO
if ($rol_sistema === 'administrativo') {
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
    if ($res) {
        while($row = $res->fetch_assoc()) { $menu_data[] = $row; }
    }
}
?>

<nav class="navbar">
    <div class="nav-main">
        <ul class="menu-list">
            <li><a href="#" class="nav-link" data-view="principal.php">Inicio</a></li>
            
            <?php if ($rol_sistema === 'administrativo'): ?>
                <?php foreach ($menu_data as $m): ?>
                    <?php if ($m['url'] !== 'principal.php'): ?>
                        <li>
                            <a href="#" class="nav-link" data-view="<?php echo $m['url']; ?>">
                                <?php echo $m['nombre_texto']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            <?php elseif ($rol_sistema === 'estudiante'): ?>
                <li><a href="#" class="nav-link" data-view="cursos_inscripcion.php">Inscribirse</a></li>
                <li><a href="#" class="nav-link" data-view="calificaciones.php">Mis Notas</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="user-dropdown">
        <div class="user-trigger">
            <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuario'; ?></strong>
            <small id="reloj-sesion" style="display: block; font-size: 0.7rem; color: #bdc3c7;">
                <?php echo isset($_SESSION['user_log']) ? $_SESSION['user_log'] : ''; ?>
            </small>
            <small style="color: #63b3ed;"><?php echo strtoupper($rol_sistema); ?></small>
        </div>
        <ul class="user-menu">
            <li><a href="server/logout.php" class="logout-link">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualizarRelojSesion() {
        const ahora = new Date();
        const texto = ahora.getDate().toString().padStart(2,'0') + '/' + 
                     (ahora.getMonth()+1).toString().padStart(2,'0') + '/' + 
                     ahora.getFullYear() + ' ' + 
                     ahora.getHours().toString().padStart(2,'0') + ':' + 
                     ahora.getMinutes().toString().padStart(2,'0') + ':' + 
                     ahora.getSeconds().toString().padStart(2,'0');
        const etiq = document.getElementById('reloj-sesion');
        if (etiq) etiq.innerText = texto;
    }
    setInterval(actualizarRelojSesion, 1000);

    const links = document.querySelectorAll('.nav-link');
    window.activarMenu = function(vista) {
        links.forEach(link => link.classList.remove('active'));
        // Truco para limpiar parámetros URL si los hubiera
        const vistaLimpia = vista.split('?')[0]; 
        const linkActivo = document.querySelector(`.nav-link[data-view="${vistaLimpia}"]`);
        if (linkActivo) linkActivo.classList.add('active');
    };
    
    // Asignar eventos click
    links.forEach(link => {
        link.addEventListener('click', function() {
            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Activar inicio por defecto
    activarMenu('principal.php');
});
</script>