<?php
require_once '../server/db.php';
?>
<div class="contenedor">
    <h2>Registrar Nuevo Usuario</h2>
    <form id="form-crear-usuario">
        <label>Nombre Real:</label>
        <input type="text" name="nombre_real" style="width:100%; padding:8px; margin-bottom:15px;" required>
        
        <label>Username:</label>
        <input type="text" name="username" style="width:100%; padding:8px; margin-bottom:15px;" required>
        
        <label>Contraseña:</label>
        <input type="password" name="password" style="width:100%; padding:8px; margin-bottom:15px;" required>

        <label>Asignar Rol:</label>
        <select name="id_rol" style="width:100%; padding:8px; margin-bottom:15px;" required>
            <option value="">-- Seleccione un Rol --</option>
            <?php
            $roles = $conn->query("SELECT id_rol, nombre_rol FROM roles");
            while($r = $roles->fetch_assoc()):
            ?>
                <option value="<?php echo $r['id_rol']; ?>"><?php echo $r['nombre_rol']; ?></option>
            <?php endwhile; ?>
        </select>

        <div style="margin-top:10px;">
            <button type="button" onclick="guardarNuevoUsuario()" class="btn-success">Crear Usuario</button>
            <button type="button" onclick="cargarVista('usuarios.php')">Cancelar</button>
        </div>
    </form>
</div>

<script>
window.guardarNuevoUsuario = function() {
    const form = document.getElementById('form-crear-usuario');
    const datos = new FormData(form);
    
    // Validación básica
    if(datos.get('id_rol') === "") {
        alert("Por favor, selecciona un rol para el usuario.");
        return;
    }

    fetch('server/usuarios_guardar.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.text())
    .then(data => {
        alert(data);
        cargarVista('usuarios.php');
    })
    .catch(err => alert("Error de conexión"));
}
</script>