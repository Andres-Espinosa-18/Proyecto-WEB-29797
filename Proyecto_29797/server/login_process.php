<?php
session_start();
require_once 'db.php';
require_once 'funciones_auditoria.php';
date_default_timezone_set('America/Guayaquil');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['user']);
    $pass_input = trim($_POST['pass']);

    // --- 1. INTENTO EN TABLA USUARIOS (Administrativos/Docentes) ---
    $stmt = $conn->prepare("SELECT id_usuario, username, password, nombre_real, estado FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $res = $stmt->get_result();
    $usuario = $res->fetch_assoc();
    $stmt->close();

    if ($usuario && password_verify($pass_input, $usuario['password'])) {
        if ($usuario['estado'] == 1) {
            // Login Administrativo Exitoso
            configurarSesion($usuario['id_usuario'], $usuario['username'], $usuario['nombre_real'], 'administrativo');
            registrarEvento($conn, "Usuario Administrativo inició sesión");
            header("Location: ../index.php"); // Éxito
        } else {
            // CORRECCIÓN AQUÍ: Redirigir a index.php, no a login.php
            header("Location: ../index.php?error=inactivo");
        }
        exit();
    }

    // --- 2. INTENTO EN TABLA ESTUDIANTES (Alumnos) ---
    $stmt2 = $conn->prepare("SELECT id_estudiante, usuario, password, nombre, estado FROM estudiantes WHERE usuario = ?");
    $stmt2->bind_param("s", $user_input);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $estudiante = $res2->fetch_assoc();
    $stmt2->close();

    if ($estudiante && password_verify($pass_input, $estudiante['password'])) {
        if ($estudiante['estado'] == 1) {
            // Login Estudiante Exitoso
            configurarSesion($estudiante['id_estudiante'], $estudiante['usuario'], $estudiante['nombre'], 'estudiante');
            
            // Truco para auditoría (evitar error de llave foránea si existe)
            $_SESSION['id_usuario'] = 0; 
            registrarEvento($conn, "Estudiante inició sesión: " . $estudiante['usuario']);
            $_SESSION['id_usuario'] = $estudiante['id_estudiante']; 
            
            header("Location: ../index.php"); // Éxito
        } else {
            // CORRECCIÓN AQUÍ: Redirigir a index.php
            header("Location: ../index.php?error=inactivo");
        }
        exit();
    }

    // Si falla en ambos (Usuario o Contraseña incorrectos)
    // CORRECCIÓN AQUÍ: Redirigir a index.php
    header("Location: ../index.php?error=credenciales");
    exit();
}

function configurarSesion($id, $username, $nombre, $rol) {
    $_SESSION['id_usuario']  = $id;
    $_SESSION['username']    = $username;
    $_SESSION['nombre_real'] = $nombre;
    $_SESSION['rol_sistema'] = $rol;
    $_SESSION['user_log']    = date("Y-m-d H:i:s");
}
?>