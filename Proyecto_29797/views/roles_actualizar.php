<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);
$res = $conn->query("SELECT * FROM roles WHERE id_rol = $id");
$r = $res->fetch_assoc();
?>

<div class="header-complex" style="margin-bottom: 20px;">
    <h3 style="color: var(--primary);">Editar Rol: <?php echo htmlspecialchars($r['nombre_rol']); ?></h3>
</div>

<form id="form-edit-rol" onsubmit="event.preventDefault();">
    <input type="hidden" name="id_rol" value="<?php echo $id; ?>">
    
    <div class="form-group">
        <label>Nombre del Rol:</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($r['nombre_rol']); ?>" readonly>
    </div>

    <div class="form-group" style="margin-top: 15px;">
        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control" style="height:80px;"><?php echo htmlspecialchars($r['descripcion']); ?></textarea>
    </div>

    <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
        <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
        <button type="button" onclick="window.guardarEdicionRol()" class="btn btn-success">&#128190; Guardar Cambios</button>
    </div>
</form>

<script>
    window.guardarEdicionRol = function() {
        const form = document.getElementById('form-edit-rol');
        const data = new FormData(form);

        fetch('server/roles_update.php', { method: 'POST', body: data })
        .then(response => response.text())
        .then(texto => {
            // CORRECCIÓN 2: Validamos 'ok' que es lo que envía tu roles_update.php
            if (texto.trim() === 'ok') {
                cerrarModal(); // Cerramos la ventana
                
                // CORRECCIÓN 3: Actualizamos la tabla
                // Si 'crear_rol.php' es donde está tu tabla de gestión, lo cargamos de nuevo
                cargarVista('crear_rol.php');
            } else {
                // Si el servidor mandó un error, lo mostramos
                alert("Error: " + texto);
            }
        })
        .catch(err => {
            alert("Error de conexión: " + err);
        });
    };
</script>