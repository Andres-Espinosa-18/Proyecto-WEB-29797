<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_real']);
	$fecha = trim($_POST['fecha']);
	$cedula = trim($_POST['cedula']);	
	$email = trim($_POST['email']);
	$direccion = trim($_POST['direccion']);
	$id_rol = trim($_POST['id_rol']);
	$pass = $_POST['password'];
	$pass_hash = password_hash($pass, PASSWORD_BCRYPT);

    if ($id > 0 && !empty($nombre) && $id_rol >= 0 ) {
        
        $conn->begin_transaction();

        try {
            // 1. Actualizar datos básicos
            $stmt = $conn->prepare("UPDATE usuarios SET password = ?, nombre_real = ?, fecha_nacimiento = ?, cedula = ?, email = ?, direccion = ? WHERE id_usuario = ?");
            $stmt->bind_param("ssssssi", $pass_hash,$nombre,$fecha,$cedula,$email, $direccion ,$id);
            $stmt->execute();
			
			// 2. Actualizar el Rol (Borramos el anterior y ponemos el nuevo)
            $conn->query("DELETE FROM usuario_roles WHERE id_usuario = $id");
            $stmt_rol = $conn->prepare("INSERT INTO usuario_roles (id_usuario, id_rol) VALUES (?, ?)");
            $stmt_rol->bind_param("ii", $id, $id_rol);
            $stmt_rol->execute();

            // 3. Obtener nombre del rol para la auditoría
            $res_n = $conn->query("SELECT nombre_rol FROM roles WHERE id_rol = $id_rol");
            $n_rol = $res_n->fetch_assoc()['nombre_rol'];

            // 4. Auditoría
            registrarEvento($conn, "Actualizó al usuario ID $id");

            $conn->commit();
            echo "Usuario y Rol actualizados con éxito.";


        } catch (Exception $e) {
            $conn->rollback();
            echo "Error al actualizar: " . $e->getMessage();
        }
    } else {
        echo "Error: Datos incompletos.";
    }
}
?>