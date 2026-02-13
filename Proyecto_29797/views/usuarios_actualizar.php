<?php
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);

// Buscamos los datos actuales del usuario
$sql = "SELECT u.*, ur.id_rol FROM usuarios u 
        LEFT JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario 
        WHERE u.id_usuario = $id";
$res = $conn->query($sql);
$u = $res->fetch_assoc();

if(!$u) { echo "<p style='color:red; text-align:center;'>Error: Usuario no encontrado.</p>"; exit; }
?>

<div>
    <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px; color:#2c3e50;">
        Editar Usuario: <small><?php echo htmlspecialchars($u['username']); ?></small>
    </h3>
    
    <form id="form-edit-user">
        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
        
        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($u['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label>Apellido:</label>
                <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($u['apellido']); ?>" >
            </div>
        </div>

        <div class="form-group">
            <label>C&eacute;dula:</label>
            <input type="text" name="cedula" class="form-control" value="<?php echo htmlspecialchars($u['cedula']); ?>">
        </div>

        <div class="form-group">
            <label>Password (Dejar vac&iacute;o para no cambiar):</label>
            <input type="password" name="password" class="form-control" placeholder="------">
        </div>

        <div class="form-group">
            <label>Rol:</label>
            <select name="id_rol" class="form-control">
                <?php 
                $roles = $conn->query("SELECT * FROM roles");
                while($r = $roles->fetch_assoc()): 
                    $sel = ($u['id_rol'] == $r['id_rol']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $r['id_rol']; ?>" <?php echo $sel; ?>>
                        <?php echo $r['nombre_rol']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div style="text-align:right; margin-top:20px; padding-top:10px; border-top:1px solid #eee;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" onclick="window.guardarEdicionUsuario()">&#128190; Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
    // --- SOLUCIÓN DEL PROBLEMA "NO HACE NADA" ---
    // Usamos 'window.nombreFuncion' para hacerla accesible globalmente desde el modal
    window.guardarEdicionUsuario = function() {
        console.log("Intentando guardar..."); // Para depuración en consola

        const form = document.getElementById('form-edit-user');
        
       

        const data = new FormData(form);

        // Enviar datos al servidor
        fetch('server/usuarios_update.php', { method: 'POST', body: data })
        .then(response => response.text())
        .then(texto => {
            alert(texto); // Mostrar respuesta del servidor
            if (texto.includes('correctamente') || texto.includes('Exito')) {
                cerrarModal();
                cargarVista('usuarios.php'); // Recargar la tabla
            }
        })
        .catch(err => {
            alert("Error de conexión: " + err);
        });
    };
</script>