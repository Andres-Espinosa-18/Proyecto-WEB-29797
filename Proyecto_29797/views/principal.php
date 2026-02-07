<?php
// Ya no necesitas session_start ni db.php aquí porque vienen del index.php
?>
<div class="contenedor">
    <h1>Bienvenido, <?php echo $_SESSION['nombre_real']; ?></h1>
    <p>Has ingresado al sistema correctamente.</p>
    <div class="dashboard-grid">
        <div class="card clickable" onclick="cargarVista('usuarios.php')">
            <h3>Gestionar Usuarios</h3>
            <p>Añadir o editar personal</p>
        </div>
        <div class="card info-only">
            <h3>Tu Último Acceso</h3>
            <p><?php echo $_SESSION['user_log']; ?></p>
        </div>
    </div>
</div>