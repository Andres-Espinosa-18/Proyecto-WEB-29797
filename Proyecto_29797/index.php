<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'server/db.php';

// 1. Verificación de sesión: Si no hay usuario, mostramos el fragmento de login y cortamos la ejecución.
if (!isset($_SESSION['id_usuario'])) {
    include 'views/login.php'; 
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Proyecto_29797 | SPA</title>
    <link rel="stylesheet" href="style/style.css">
</head>
<body>

    <header id="navbar-container">
        <?php include 'includes/navbar.php'; ?>
    </header>

    <main id="main-content" class="contenedor">
        <div class="loader-placeholder">
            <p>Cargando panel de control...</p>
        </div>
    </main>

    <script src="scripts/spa.js"></script>
    
    <script>
        // Al cargar la página por primera vez, forzamos la carga de la vista principal
	    const contenido = document.getElementById('main-content');
		contenido.innerHTML = "<p>Cargando sección...</p>";

        window.addEventListener('DOMContentLoaded', () => {
            // Verificamos si hay un error en la URL para mostrar alertas
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                console.warn("Acceso denegado o error de sesión detectado.");
            }
            
            // Carga por defecto el dashboard/principal
            cargarVista('principal.php');
        });
    </script>
</body>
</html>