<?php
session_start();
require_once 'db.php';
require_once 'funciones_auditoria.php';

// --- 1. VERIFICAR BLOQUEO ACTIVO ---
if (isset($_SESSION['tiempo_bloqueo'])) {
    if (time() < $_SESSION['tiempo_bloqueo']) {
        // Todavía está bloqueado
        $tiempo_restante = $_SESSION['tiempo_bloqueo'] - time();
        // Ajusta la redirección según donde esté tu login (usualmente ../index.php o ../login.php)
        header("Location: ../index.php?error=bloqueado&tiempo=$tiempo_restante");
        exit();
    } else {
        // El tiempo ya pasó, reseteamos el bloqueo
        unset($_SESSION['tiempo_bloqueo']);
        $_SESSION['intentos_fallidos'] = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['user']);
    $pass_input = trim($_POST['pass']);

    // --- 2. INTENTAR COMO ADMINISTRATIVO/DOCENTE ---
    $stmt = $conn->prepare("SELECT id_usuario, username, password, nombre, apellido, estado FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();

    if ($usuario && password_verify($pass_input, $usuario['password'])) {
        if ($usuario['estado'] == 1) {
            // ÉXITO: Limpiamos contadores de error
            unset($_SESSION['intentos_fallidos']);
            unset($_SESSION['tiempo_bloqueo']);

            configurarSesion($usuario['id_usuario'], $usuario['username'], $usuario['nombre'], $usuario['apellido'], 'administrativo');
            registrarEvento($conn, "Usuario Administrativo entró");
            header("Location: ../index.php");
        } else {
            header("Location: ../index.php?error=inactivo");
        }
        exit();
    }

    // --- 3. INTENTAR COMO ESTUDIANTE ---
    $stmt2 = $conn->prepare("SELECT id_estudiante, usuario, password, nombre, apellido, estado FROM estudiantes WHERE usuario = ?");
    $stmt2->bind_param("s", $user_input);
    $stmt2->execute();
    $est = $stmt2->get_result()->fetch_assoc();

    if ($est && password_verify($pass_input, $est['password'])) {
        if ($est['estado'] == 1) {
            // ÉXITO: Limpiamos contadores de error
            unset($_SESSION['intentos_fallidos']);
            unset($_SESSION['tiempo_bloqueo']);

            configurarSesion($est['id_estudiante'], $est['usuario'], $est['nombre'], $est['apellido'], 'estudiante');
            registrarEvento($conn, "Estudiante entró: " . $est['usuario']);
            header("Location: ../index.php");
        } else {
            header("Location: ../index.php?error=inactivo");
        }
        exit();
    }

    // --- 4. SI LLEGA AQUÍ, FALLÓ LA CONTRASEÑA ---
    
    // Inicializar intentos si no existen
    if (!isset($_SESSION['intentos_fallidos'])) {
        $_SESSION['intentos_fallidos'] = 0;
    }

    // Sumar un intento fallido
    $_SESSION['intentos_fallidos']++;

    // Verificar si llegó al límite de 3
    if ($_SESSION['intentos_fallidos'] >= 3) {
        // BLOQUEAR POR 10 SEGUNDOS
        $_SESSION['tiempo_bloqueo'] = time() + 10;
        header("Location: ../index.php?error=bloqueado&tiempo=10");
    } else {
        // Error normal (aún tiene intentos)
        header("Location: ../index.php?error=credenciales");
    }
    exit();
}

// Función auxiliar de sesión
function configurarSesion($id, $user, $nom, $ape, $rol) {
    $_SESSION['id_usuario']  = $id; 
    $_SESSION['username']    = $user;
    $_SESSION['nombre_real'] = $nom . " " . $ape;
    $_SESSION['rol_sistema'] = $rol;

    if ($rol === 'estudiante') {
        $_SESSION['id_estudiante'] = $id;
    } else {
        unset($_SESSION['id_estudiante']);
    }
}
?>