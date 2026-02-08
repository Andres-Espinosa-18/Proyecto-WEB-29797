<?php
require_once 'funciones_auditoria.php';
require_once 'db.php';
session_start(); // Unirse a la sesión actual para poder destruirla
registrarEvento($conn, "Ha cerrado sesión");
// 1. Limpiar todas las variables de sesión
$_SESSION = array();

// 2. Si se desea destruir la cookie de sesión también (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}



// 3. Destruir la sesión en el servidor
session_destroy();

// 4. Redirigir al login (index.php que está una carpeta arriba)
header("Location: ../index.php");
exit();
?>