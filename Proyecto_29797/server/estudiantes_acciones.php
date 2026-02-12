<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';
session_start();

// Solo permitir acceso si es administrativo
if (!isset($_SESSION['rol_sistema']) || $_SESSION['rol_sistema'] !== 'administrativo') {
    die("Acceso denegado.");
}

$accion = $_POST['accion'] ?? '';

// --- 1. CREAR ESTUDIANTE ---
if ($accion === 'crear') {
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $usuario = trim($_POST['usuario']);
    $pass = $_POST['password'];
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $direccion = trim($_POST['direccion'] ?? '');
    $carrera = trim($_POST['carrera'] ?? '');
    $fecha = trim($_POST['fecha_nacimiento'] ?? '');
    if($fecha == '') $fecha = null;

    $check = $conn->query("SELECT id_estudiante FROM estudiantes WHERE usuario = '$usuario' OR cedula = '$cedula'");
    if ($check->num_rows > 0) {
        echo "Error: El usuario o la cédula ya existen.";
        exit;
    }

    $pass_hash = password_hash($pass, PASSWORD_BCRYPT);
    
    $stmt = $conn->prepare("INSERT INTO estudiantes (nombre, cedula, usuario, password, correo, telefono, direccion, carrera, fecha_nacimiento, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("sssssssss", $nombre, $cedula, $usuario, $pass_hash, $correo, $telefono, $direccion, $carrera, $fecha);

    if ($stmt->execute()) {
        registrarEvento($conn, "Creó al estudiante: $nombre ($cedula)");
        echo "Estudiante creado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }

// --- 2. EDITAR ESTUDIANTE (MODIFICADO: NO TOCA EL ESTADO) ---
} elseif ($accion === 'editar') {
    $id = intval($_POST['id_estudiante']);
    $nombre = trim($_POST['nombre']);
    $cedula = trim($_POST['cedula']);
    $correo = trim($_POST['correo']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $carrera = trim($_POST['carrera']);
    $fecha = trim($_POST['fecha_nacimiento']);
    if($fecha == '') $fecha = null;
    
    // Contraseña (Solo si se envía)
    $sql_pass = "";
    if (!empty($_POST['password'])) {
        $ph = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql_pass = ", password = '$ph'";
    }

    // QUERY ACTUALIZADA: Se eliminó 'estado=?' de la lista
    $stmt = $conn->prepare("UPDATE estudiantes SET nombre=?, cedula=?, correo=?, telefono=?, direccion=?, carrera=?, fecha_nacimiento=? $sql_pass WHERE id_estudiante=?");
    
    // BIND PARAM ACTUALIZADO: 7 strings (s) y 1 entero (i) para el ID
    $stmt->bind_param("sssssssi", $nombre, $cedula, $correo, $telefono, $direccion, $carrera, $fecha, $id);

    if ($stmt->execute()) {
        registrarEvento($conn, "Editó datos del estudiante ID: $id");
        echo "Estudiante actualizado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }

// --- 3. CAMBIAR ESTADO (ACTIVAR / ELIMINAR LÓGICO) ---
} elseif ($accion === 'cambiar_estado') {
    $id = intval($_POST['id']);
    $nuevo_estado = intval($_POST['estado']); // 1 = Activar, 0 = Inactivar
    
    // Obtener nombre para auditoría
    $res = $conn->query("SELECT nombre FROM estudiantes WHERE id_estudiante = $id");
    $nom = $res->fetch_assoc()['nombre'] ?? 'Desconocido';

    $stmt = $conn->prepare("UPDATE estudiantes SET estado = ? WHERE id_estudiante = ?");
    $stmt->bind_param("ii", $nuevo_estado, $id);
    
    if ($stmt->execute()) {
        $verbo = ($nuevo_estado == 1) ? "Activó" : "Inactivó";
        registrarEvento($conn, "$verbo al estudiante: $nom");
        echo "Estado del estudiante actualizado.";
    } else {
        echo "Error al cambiar estado: " . $conn->error;
    }
}
?>