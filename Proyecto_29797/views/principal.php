<?php
// Si este archivo se carga por AJAX dentro del index, db.php ya está cargado.
// Si lo pruebas directo, descomenta la línea siguiente:
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('America/Guayaquil');
require_once '../server/db.php'; 

// PASO 1: OBTENER EL ROL AUTOMÁTICAMENTE USANDO LA SESIÓN
$id_usuario = $_SESSION['id_usuario']; // Esto viene del login

// Buscamos qué rol tiene este usuario en la BD
$sql_rol = "SELECT id_rol FROM usuario_roles WHERE id_usuario = '$id_usuario'";
$res_rol = $conn->query($sql_rol);

if ($res_rol && $res_rol->num_rows > 0) {
    $fila = $res_rol->fetch_assoc();
    $id_rol = $fila['id_rol']; // ¡Ahora sí tenemos el ID correcto!
} else {
    $id_rol = 0; // Por seguridad, si no tiene rol
}
?>

<div class="contenedor">
    <h1>Bienvenido, <?php echo $_SESSION['nombre_real']; ?></h1>
    <p>Has ingresado al sistema correctamente.</p>
    
    <div class="dashboard-grid">
        <div class="card clickable" onclick="cargarVista('usuarios.php')">
            <h3>Gestionar Usuarios</h3>
        </div>
        <div class="card info-only">
            <h3>Tu Último Acceso</h3>
            <p><?php echo $_SESSION['user_log'] ?? date('Y-m-d H:i'); ?></p>
        </div>
    </div>
   
</div>