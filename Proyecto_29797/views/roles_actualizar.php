<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../server/db.php';
$id_usuario = $_SESSION['id_usuario']; // Esto viene del login

$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM roles WHERE id_rol = $id");
$r = $res->fetch_assoc();
?>
<div class="contenedor">
    <h3>Editar Rol: <?php echo htmlspecialchars($r['nombre_rol']); ?></h3>
    <form id="form-edit-rol">
        <input type="hidden" name="id_rol" value="<?php echo $id; ?>">
        
		<?php  if( $id_usuario==1): ?>
        <label>Nombre del Rol:</label>
        <input type="text" name="nombre_rol" value="<?php echo htmlspecialchars($r['nombre_rol']); ?>" required>
		<?php endif; ?>
        
        <label>Descripción:</label>
        <textarea name="descripcion" style="width:100%; height:80px; margin-bottom:15px;"><?php echo htmlspecialchars($r['descripcion']); ?></textarea>

        <button type="button" onclick="enviarActualizacionRol()" class="btn-success">Guardar Cambios</button>
        <button type="button" onclick="cargarVista('crear_rol.php')">Cancelar</button>
    </form>
</div>

<script>
window.enviarActualizacionRol = function() {
    const datos = new FormData(document.getElementById('form-edit-rol'));
    fetch('server/roles_update.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(data => {
        alert(data);
        cargarVista('crear_rol.php');
    })
    .catch(err => alert("Error de conexión"));
}
</script>