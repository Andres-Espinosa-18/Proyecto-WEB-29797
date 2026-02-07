<?php
session_start();
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['user'];
    $pass_input = $_POST['pass'];

    $stmt = $conn->prepare("SELECT id_usuario, username, password, nombre_real FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($pass_input, $user['password'])) {
            // Guardamos los datos en la sesión
            $_SESSION['id_usuario'] = $user['id_usuario'];
            $_SESSION['username']   = $user['username'];
            $_SESSION['nombre_real'] = $user['nombre_real'];
            $_SESSION['user_log']   = date("d/m/Y H:i:s");

            $id = $user['id_usuario'];
            $conn->query("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = $id");

            // --- CAMBIO AQUÍ ---
            // Redirigimos a la RAÍZ. El index.php detectará la sesión 
            // y cargará automáticamente el Dashboard y el Navbar.
            header("Location: ../index.php");
            exit();
        }
    }
    
    // Si falla, volvemos al index. El index.php cargará el fragmento de login con el error.
    header("Location: ../index.php?error=credenciales");
    exit();
}
?>