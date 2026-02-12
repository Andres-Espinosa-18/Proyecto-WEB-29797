<?php
// --- BLOQUE LÓGICO: ESTO ES NECESARIO PARA QUE NO TE DE ERROR ---
require_once 'server/db.php'; // Asegúrate de que esta ruta sea correcta según tu estructura
$user_id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;

// Consulta para obtener los menús
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
// -------------------------------------------------------------
?>

<nav class="navbar">
    <ul class="menu-list">
        <li><a href="#" class="nav-link" data-view="principal.php">Inicio</a></li>
        
        <?php if (!empty($menu_data)): ?>
            <?php foreach ($menu_data as $m): ?>
                <?php if ($m['url'] !== 'principal.php'): ?>
                    <li>
                        <a href="#" class="nav-link" data-view="<?php echo $m['url']; ?>">
                            <?php echo $m['nombre_texto']; ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <div class="user-dropdown">
        <div class="user-trigger">
            <strong><?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Usuario'; ?></strong>
            
            <?php if(isset($_SESSION['user_log'])): ?>
                <small id="reloj-sesion" style="display: block; font-size: 0.7rem; color: #bdc3c7;">
                    <?php echo $_SESSION['user_log']; ?>
                </small>
            <?php endif; ?>
            
        </div>
        <ul class="user-menu">
            <li><a href="server/logout.php" class="logout-link">Cerrar Sesión</a></li>
        </ul>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- RELOJ EN TIEMPO REAL ---
    function actualizarRelojSesion() {
        const ahora = new Date();
        const dia = String(ahora.getDate()).padStart(2, '0');
        const mes = String(ahora.getMonth() + 1).padStart(2, '0');
        const anio = ahora.getFullYear();
        const horas = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');
        const segundos = String(ahora.getSeconds()).padStart(2, '0');
        
        // Actualiza el texto con la hora actual
        const etiquetaReloj = document.getElementById('reloj-sesion');
        if (etiquetaReloj) {
            etiquetaReloj.innerText = `${dia}/${mes}/${anio} ${horas}:${minutos}:${segundos}`;
        }
    }
    // Activa el reloj cada segundo
    setInterval(actualizarRelojSesion, 1000);


    // --- LÓGICA DE NAVEGACIÓN (TUS MENÚS) ---
    const links = document.querySelectorAll('.nav-link');

    window.activarMenu = function(vista) {
        links.forEach(link => link.classList.remove('active'));
        const vistaLimpia = vista.split('?')[0]; 
        const linkActivo = document.querySelector(`.nav-link[data-view="${vistaLimpia}"]`);
        if (linkActivo) linkActivo.classList.add('active');
    };

    links.forEach(link => {
        link.addEventListener('click', function() {
            links.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    activarMenu('principal.php');
});
</script>