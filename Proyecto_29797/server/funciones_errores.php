<?php
// server/funciones_errores.php

function registrarError($mensaje, $archivo = 'Desconocido') {
    // 1. Definir la ruta del archivo log
    $archivo_log = __DIR__ . '/sistema_errores.log';

    // 2. Obtener datos de la sesión actual (Quién generó el error)
    // Usamos 'session_status' por si la sesión no estaba iniciada
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $usuario = isset($_SESSION['username']) ? $_SESSION['username'] : 'Invitado';
    $rol = isset($_SESSION['rol_sistema']) ? $_SESSION['rol_sistema'] : 'N/A';

    // 3. Formato de la línea: FECHA | USUARIO (ROL) | ARCHIVO | MENSAJE
    $fecha = date('Y-m-d H:i:s');
    $linea = "$fecha | $usuario ($rol) | $archivo | $mensaje" . PHP_EOL;

    // 4. Guardar en el archivo (FILE_APPEND evita que se borre lo anterior)
    file_put_contents($archivo_log, $linea, FILE_APPEND);
}
?>