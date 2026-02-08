<?php
session_start();
require_once 'db.php';
require_once 'funciones_auditoria.php';

// 1. BLOQUEO TEMPORAL
if (isset($_SESSION['tiempo_bloqueo'])) {
    if (time() < $_SESSION['tiempo_bloqueo']) {
        $restante = $_SESSION['tiempo_bloqueo'] - time();
        header("Location: ../index.php?error=bloqueado&segundos=$restante");
        exit();
    } else {
        unset($_SESSION['tiempo_bloqueo']);
        $_SESSION['intentos'] = 0;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['user']);
    $pass_input = trim($_POST['pass']);

    // Buscamos al usuario
    $stmt = $conn->prepare("SELECT id_usuario, username, password, nombre_real, estado FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Obtenemos los datos
    $user = $result->fetch_assoc();

    //Cerramos la consulta de búsqueda AQUÍ para liberar la conexión
    // Si no haces esto, el INSERT de auditoría fallará porque la BD está ocupada.
    $stmt->close(); 

    $mensaje_error = "credenciales"; 

    // Verificamos si encontramos al usuario (ahora usamos la variable $user que guardamos antes)
    if ($user) {
        
        // 2. VERIFICAR CONTRASEÑA
        if (password_verify($pass_input, $user['password'])) {
            
            // 3. VERIFICAR ESTADO
            if ($user['estado'] == 1) {
                // --- ÉXITO ---
                unset($_SESSION['intentos']);
                unset($_SESSION['tiempo_bloqueo']);

                $_SESSION['id_usuario']  = $user['id_usuario'];
                $_SESSION['username']    = $user['username'];
                $_SESSION['nombre_real'] = $user['nombre_real'];
                $_SESSION['user_log']    = date("d/m/Y H:i:s");

                // Actualizar último acceso (Aquí usamos query simple, no prepare, así que no choca)
                $conn->query("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = " . $user['id_usuario']);
                
                registrarEvento($conn, "Ha iniciado sesión correctamente");

                header("Location: ../index.php");
                exit();

            } else {
                // --- USUARIO INACTIVO ---
                $mensaje_error = "inactivo";
                
                // Registro manual de auditoría
                $usuario_nombre = $user['username'];
                $id_u = $user['id_usuario'];
                $accion = "Intento de acceso: Usuario inactivo";
                $ip = $_SERVER['REMOTE_ADDR'] ?? '::1';

                $stmt_aud = $conn->prepare("INSERT INTO auditoria (id_usuario, usuario_nombre, accion, ip_conexion) VALUES (?, ?, ?, ?)");
                $stmt_aud->bind_param("isss", $id_u, $usuario_nombre, $accion, $ip);
                if(!$stmt_aud->execute()) { error_log("Error Auditoria: " . $stmt_aud->error); } // Log de error por si acaso
                $stmt_aud->close();
            }

        } else {
            // --- CONTRASEÑA INCORRECTA ---
            $usuario_nombre = $user['username'];
            $id_u = $user['id_usuario'];
            $num_intento = isset($_SESSION['intentos']) ? $_SESSION['intentos'] + 1 : 1;
            $accion = "Fallo de contraseña (Intento $num_intento)";
            $ip = $_SERVER['REMOTE_ADDR'] ?? '::1';
            
            $stmt_aud = $conn->prepare("INSERT INTO auditoria (id_usuario, usuario_nombre, accion, ip_conexion) VALUES (?, ?, ?, ?)");
            $stmt_aud->bind_param("isss", $id_u, $usuario_nombre, $accion, $ip);
            if(!$stmt_aud->execute()) { error_log("Error Auditoria: " . $stmt_aud->error); }
            $stmt_aud->close();
        }
    } else {
        // --- USUARIO NO EXISTE ---
        $accion = "Intento de acceso con usuario no existente: " . $user_input;
        $ip = $_SERVER['REMOTE_ADDR'] ?? '::1';
        
        // Aquí NO enviamos id_usuario porque no existe
        $stmt_aud = $conn->prepare("INSERT INTO auditoria (usuario_nombre, accion, ip_conexion) VALUES (?, ?, ?)");
        $stmt_aud->bind_param("sss", $user_input, $accion, $ip);
        if(!$stmt_aud->execute()) { error_log("Error Auditoria: " . $stmt_aud->error); }
        $stmt_aud->close();
    }

    // 4. GESTIÓN DE INTENTOS
    if (!isset($_SESSION['intentos'])) {
        $_SESSION['intentos'] = 1;
    } else {
        $_SESSION['intentos']++;
    }

    if ($_SESSION['intentos'] >= 3) {
        $_SESSION['tiempo_bloqueo'] = time() + 10;
        header("Location: ../index.php?error=bloqueado&segundos=10");
    } else {
        header("Location: ../index.php?error=" . $mensaje_error);
    }
    exit();
}
?>