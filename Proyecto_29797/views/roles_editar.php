<?php
require_once '../server/db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$r = $conn->query("SELECT * FROM roles WHERE id_rol=$id")->fetch_assoc();
if(!$r) { echo "Rol no encontrado."; exit; }
?>

<div style="padding:10px;">
    <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Editar Rol</h3>
    <form id="form-editar-rol">
        <input type="hidden" name="id_rol" value="<?php echo $id; ?>">
        
  
		
        
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="3"><?php echo htmlspecialchars($r['descripcion']); ?></textarea>
        </div>

        <div style="text-align:right; margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" id="btnEditarRol">Guardar Cambios</button>
        </div>
    </form>
</div>

<script>
(function() {
    var btn = document.getElementById('btnEditarRol');
    if(btn) {
        btn.onclick = function() {
            var f = document.getElementById('form-editar-rol');
            if(f.nombre_rol.value.trim() === "") { alert("Nombre obligatorio"); return; }
            
            var d = new FormData(f);
            fetch('server/roles_update.php', { method:'POST', body:d })
            .then(r => r.text())
            .then(m => { 
                alert(m); 
                if(m.indexOf('correctamente') !== -1) { 
                    cerrarModal(); 
                    if(typeof buscarRoles === 'function') buscarRoles(1); 
                }
            });
        };
    }
})();
</script>