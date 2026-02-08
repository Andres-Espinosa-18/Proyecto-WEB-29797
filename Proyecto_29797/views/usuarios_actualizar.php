<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);


// PASO 1: OBTENER EL ROL AUTOMÁTICAMENTE USANDO LA SESIÓN
$id_usuario = $_SESSION['id_usuario']; // Esto viene del login

// 1. Obtenemos los datos del usuario
// Usamos LEFT JOIN para que traiga al usuario incluso si no tiene rol asignado
$sql = "SELECT u.*, ur.id_rol, ro.nombre_rol 
        FROM usuarios u 
        LEFT JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario 
		LEFT JOIN roles ro ON ro.id_rol= ur.id_rol
        WHERE u.id_usuario = $id";
$res = $conn->query($sql);
$u = $res->fetch_assoc();

// 2. Obtenemos TODOS los roles disponibles
$roles = $conn->query("SELECT id_rol, nombre_rol FROM roles");
// ¡OJO! No hacemos fetch_assoc aquí todavía, lo hacemos dentro del bucle abajo
?>

<div class="contenedor">
    <h3>Actualizar Usuario: <?php echo htmlspecialchars($u['username']); ?></h3>
    
    <form id="form-edit-user">
        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
        
        <div class="form-group">
            <label>Nombre Real:</label>
            <input type="text" name="nombre_real" value="<?php echo htmlspecialchars($u['nombre_real']); ?>" required>
        </div>
		
		 <div class="form-group">
		<?php  if( $id_usuario==1): ?>
		 <label>Contraseña:</label>
        <input type="password" name="password" style="width:100%; padding:8px; margin-bottom:15px;">
		<?php endif; ?>
		</div>
		
        <div class="form-group">
            <label>Fecha de Nacimiento:</label>
            <input type="date" name="fecha" value="<?php echo htmlspecialchars($u['fecha_nacimiento']); ?>">
        </div>

        <div class="form-group">
            <label>Cédula:</label>
            <input type="text" name="cedula" value="<?php echo htmlspecialchars($u['cedula']); ?>">
        </div>

        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>">
        </div>

        <div class="form-group">
            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?php echo htmlspecialchars($u['direccion']); ?>">
        </div>

        <div class="form-group">
            <label>Rol del Usuario:</label>
            <select name="id_rol" style="width:100%; padding:8px; margin-bottom:15px;">
                <option value="<?php echo htmlspecialchars($u['nombre_rol']); ?>"> Seleccionar</option>
                
                <?php 
                // CORRECCIÓN AQUÍ: El while debe hacer el fetch
                while($r = $roles->fetch_assoc()): 
                    // Verificamos si este es el rol que ya tiene el usuario
                    $seleccionado = ($u['id_rol'] == $r['id_rol']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $r['id_rol']; ?>" <?php echo $seleccionado; ?>>
                        <?php echo $r['nombre_rol']; ?>
                    </option>
                <?php endwhile; ?>
            
            </select>
        </div>

        <div style="margin-top:20px;">
            <button type="button" onclick="enviarActualizacionUser()" class="btn-success">Guardar Cambios</button>
            <button type="button" onclick="cargarVista('usuarios.php')" class="btn-cancel">Cancelar</button>
        </div>
    </form>
</div>

<script>
window.enviarActualizacionUser = function() {
    // 1. Validar campos básicos antes de enviar (opcional pero recomendado)
    const form = document.getElementById('form-edit-user');
    if(!form.checkValidity()) {
        alert("Por favor completa los campos requeridos.");
        return;
    }

    const datos = new FormData(form);
    
    fetch('server/usuarios_update.php', { 
        method: 'POST', 
        body: datos 
    })
    .then(res => res.text())
    .then(data => {
        // Mostramos lo que responde el servidor (sea éxito o error)
        alert(data);
        // Recargamos la lista
        cargarVista('usuarios.php');
    })
}
</script>