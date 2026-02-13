<?php
require_once 'db.php';

$accion = $_POST['accion'] ?? '';

if ($accion == 'crear') {
    $nom = $_POST['nombre'];
    $ape = $_POST['apellido'];
    $ced = $_POST['cedula'];
    $usu = $_POST['usuario'];
    $cor = $_POST['correo'];
    $cla = $_POST['clave']; // Recuerda encriptar en producción: password_hash()

    $sql = "INSERT INTO estudiantes (nombre, apellido, cedula, usuario, correo, password, estado) 
            VALUES ('$nom', '$ape', '$ced', '$usu', '$cor', '$cla', 1)";
    
    if($conn->query($sql)) echo "ok";
    else echo "Error: " . $conn->error;
}

if ($accion == 'editar') {
    $id  = $_POST['id_estudiante'];
    $nom = $_POST['nombre'];
    $ape = $_POST['apellido'];
    $ced = $_POST['cedula'];
    $usu = $_POST['usuario'];
    
    
    $sql = "UPDATE estudiantes SET nombre='$nom', apellido='$ape', cedula='$ced', usuario='$usu'";
    
    // Solo actualizamos clave si escribieron algo
    if(!empty($_POST['clave'])) {
        $cla = $_POST['clave'];
        $sql .= ", password='$cla'";
    }

    $sql .= " WHERE id_estudiante=$id";

    if($conn->query($sql)) echo "ok";
    else echo "Error: " . $conn->error;
}
?>