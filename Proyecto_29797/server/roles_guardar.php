<?php
// server/roles_guardar.php
require_once 'db.php';
require_once 'funciones_auditoria.php'; // Fundamental para la bitácora

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpiamos los datos recibidos
    $nombre = isset($_POST['nombre_rol']) ? trim($_POST['nombre_rol']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';

    if (!empty($nombre)) {
        // Usamos sentencias preparadas para evitar inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO roles (nombre_rol, descripcion) VALUES (?, ?)");
        $stmt->bind_param("ss", $nombre, $descripcion);

        if ($stmt->execute()) {
            // REGISTRO EN AUDITORÍA
            registrarEvento($conn, "Creó un nuevo rol: " . $nombre);
            echo "Éxito: El rol se ha creado.";
        } else {
            if ($conn->errno == 1062) {
                echo "Error: Ya existe un rol con ese nombre.";
            } else {
                echo "Error de base de datos: " . $conn->error;
            }
        }
        $stmt->close();
    } else {
        echo "Error: El nombre del rol no puede estar vacío.";
    }
} else {
    echo "Acceso denegado.";
}
?>