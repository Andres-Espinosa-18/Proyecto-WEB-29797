<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nombre']);
    $ape = trim($_POST['apellido']);
    $ced = trim($_POST['cedula']);
    $fec = $_POST['fecha_nacimiento'];
    $user = trim($_POST['username']);
    $pass = $_POST['password'];
    $rol = intval($_POST['id_rol']);

    // Validar edad server-side
    $edad = (new DateTime())->diff(new DateTime($fec))->y;
    if($edad < 18) { die("Error: Debe ser mayor de 18 años."); }

    // Validar duplicados (Opcional pero recomendado)
    $check = $conn->query("SELECT id_usuario FROM usuarios WHERE username = '$user' OR cedula = '$ced'");
    if ($check->num_rows > 0) {
        die("Error: El usuario o la cédula ya están registrados.");
    }

    $conn->begin_transaction();
    try {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        
        // Insert con nombre y apellido
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, cedula, fecha_nacimiento, username, password, estado) VALUES (?, ?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("ssssss", $nom, $ape, $ced, $fec, $user, $hash);
        
        if ($stmt->execute()) {
            $uid = $conn->insert_id;

            // Insertar Rol
            $conn->query("INSERT INTO usuario_roles (id_usuario, id_rol) VALUES ($uid, $rol)");
            
            // Registrar en auditoría
            if(function_exists('registrarEvento')) {
                registrarEvento($conn, "Creó usuario: $user");
            }

            $conn->commit();
            echo "Usuario guardado.";
        } else {
            throw new Exception($stmt->error);
        }

    } catch (Exception $e) {
        $conn->rollback();
        echo "Error al guardar: " . $e->getMessage();
    }
}
?>