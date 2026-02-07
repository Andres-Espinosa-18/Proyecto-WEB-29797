<?php
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);

// Obtenemos los datos del usuario y su rol actual
$sql = "SELECT u.*, ur.id_rol FROM usuarios u 
        LEFT JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario 
        WHERE u.id_usuario = $id";
$res = $conn->query($sql);
$u = $res->fetch_assoc();
?>
<div class="contenedor">
    <h3>Actualizar Usuario: <?php echo htmlspecialchars($u['username']); ?></h3>
    <form id="form-edit-user">
        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
        
        <label>Nombre Real:</label>
        <input type="text" name="nombre_real" value="<?php echo htmlspecialchars($u['nombre_real']); ?>" required>
        
        <label>Estado:</label>
        <select name="estado">
            <option value="A" <?php if($u['estado']=='A') echo 'selected'; ?>>Activo</option>
            <option value="I" <?php if($u['estado']=='I') echo 'selected'; ?>>Inactivo</option>
        </select>

        <div style="margin-top:20px;">
            <button type="button" onclick="enviarActualizacionUser()" class="btn-success">Guardar Cambios</button>
            <button type="button" onclick="cargarVista('usuarios.php')">Cancelar</button>
        </div>
    </form>
</div>

<script>
window.enviarActualizacionUser = function() {
    const datos = new FormData(document.getElementById('form-edit-user'));
    fetch('server/usuarios_update.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(data => {
        alert(data);
        cargarVista('usuarios.php');
    });
}
</script>