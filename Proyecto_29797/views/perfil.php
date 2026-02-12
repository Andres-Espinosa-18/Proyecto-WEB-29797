<?php
session_start();
require_once '../server/db.php';
$id = $_SESSION['id_usuario'];
$u = $conn->query("SELECT * FROM usuarios WHERE id_usuario = $id")->fetch_assoc();
?>
<div style="padding:10px;">
    <h3>Mi Perfil</h3>
    <form id="form-perfil">
        <label>Usuario:</label>
        <input type="text" value="<?php echo $u['username']; ?>" disabled style="width:100%; background:#eee; margin-bottom:10px;">
        
        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo $u['nombre']; ?>" style="width:100%; margin-bottom:10px;">
        
        <label>Apellido:</label>
        <input type="text" name="apellido" value="<?php echo $u['apellido']; ?>" style="width:100%; margin-bottom:10px;">
        
        <label>Nueva Contraseña (Opcional):</label>
        <input type="password" name="password" placeholder="Dejar vacío para no cambiar" style="width:100%; margin-bottom:10px;">
        
        <div style="text-align:right;">
            <button type="button" onclick="actualizarPerfil()" class="btn-success">Actualizar Datos</button>
        </div>
    </form>
</div>

<script>
function actualizarPerfil() {
    const d = new FormData(document.getElementById('form-perfil'));
    d.append('accion', 'perfil_propio');
    
    fetch('server/usuarios_update.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(msg => { alert(msg); cerrarModal(); });
}
</script>