<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $id = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
    $nombre = trim($_POST['nombre']); // Obligatorio
    $apellido = trim($_POST['apellido']); // Opcional (puede estar vacío)
    $cedula = trim($_POST['cedula']);
    $pass = $_POST['password'];
    $id_rol = isset($_POST['id_rol']) ? intval($_POST['id_rol']) : 0;

    // 1. Validación: Nombre obligatorio
    if (empty($nombre)) { 
        die("Error: El nombre es obligatorio."); 
    }

    // 2. Validación: Cédula Única (Si se escribió una cédula)
    if (!empty($cedula)) {
        // Buscamos si existe OTRO usuario con esa misma cédula
        $sql_check = "SELECT id_usuario FROM usuarios WHERE cedula = '$cedula' AND id_usuario != $id";
        $check = $conn->query($sql_check);
        if ($check->num_rows > 0) {
            die("Error: La cédula '$cedula' ya pertenece a otro usuario.");
        }
    }

    // 3. Preparar SQL Dinámico
    // Nota: Actualizamos apellido incluso si está vacío
    $sql = "UPDATE usuarios SET nombre=?, apellido=?, cedula=?";
    $types = "sss";
    $params = array($nombre, $apellido, $cedula);

    // Solo actualizamos contraseña si el usuario escribió algo
    if (!empty($pass)) {
        $hash = password_hash($pass, PASSWORD_BCRYPT);
        $sql .= ", password=?";
        $types .= "s";
        $params[] = $hash;
    }

    $sql .= " WHERE id_usuario=?";
    $types .= "i";
    $params[] = $id;

    // Ejecutar
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        // Actualizar Rol si se envió
        if ($id_rol > 0) {
            $conn->query("DELETE FROM usuario_roles WHERE id_usuario = $id");
            $conn->query("INSERT INTO usuario_roles (id_usuario, id_rol) VALUES ($id, $id_rol)");
        }
        
        if(function_exists('registrarEvento')) {
            registrarEvento($conn, "Editó usuario ID: $id");
        }
        echo "Usuario actualizado correctamente.";
    } else {
        echo "Error SQL: " . $conn->error;
    }
}
?>