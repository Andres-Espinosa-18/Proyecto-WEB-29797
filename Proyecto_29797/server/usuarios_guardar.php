<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre_real']);
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $id_rol = intval($_POST['id_rol']);
    $estado = 'A';

    if (!empty($nombre) && !empty($user) && !empty($pass) && $id_rol > 0) {
        
        // Iniciar transacción para asegurar que se creen ambas cosas o ninguna
        $conn->begin_transaction();

        try {
            // 1. Encriptar contraseña
            $pass_hash = password_hash($pass, PASSWORD_BCRYPT);

            // 2. Insertar Usuario
            $stmt = $conn->prepare("INSERT INTO usuarios (nombre_real, username, password, estado) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nombre, $user, $pass_hash, $estado);
            $stmt->execute();
            
            $new_user_id = $conn->insert_id;

            // 3. Insertar el Rol asignado
            $stmt_rol = $conn->prepare("INSERT INTO usuario_roles (id_usuario, id_rol) VALUES (?, ?)");
            $stmt_rol->bind_param("ii", $new_user_id, $id_rol);
            $stmt_rol->execute();

            // 4. Auditoría
            registrarEvento($conn, "Creó al usuario: $user y le asignó el Rol ID: $id_rol");

            $conn->commit();
            echo "Usuario creado exitosamente con su rol correspondiente.";

        } catch (Exception $e) {
            $conn->rollback();
            echo "Error al crear: " . $e->getMessage();
        }
    } else {
        echo "Error: Todos los campos son obligatorios.";
    }
}
?>