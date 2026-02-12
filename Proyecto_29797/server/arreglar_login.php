<?php
require_once 'db.php';

// Contraseña que queremos usar: 12345
$pass_segura = password_hash('12345', PASSWORD_BCRYPT);

// Actualizamos al estudiante 'est1'
$sql = "UPDATE estudiantes SET password = '$pass_segura', estado = 1 WHERE usuario = 'est1'";

if ($conn->query($sql)) {
    echo "<h1>¡CORREGIDO!</h1>";
    echo "<p>La contraseña del usuario <b>est1</b> se ha reseteado a: <b>12345</b></p>";
    echo "<p>El estado se ha forzado a '1' (Activo).</p>";
    echo "<br><a href='index.php'>--> INTENTAR ENTRAR AHORA <--</a>";
} else {
    echo "Error al corregir: " . $conn->error;
}
?>