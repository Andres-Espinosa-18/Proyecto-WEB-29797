<div style="padding:10px;">
    <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Nuevo Rol</h3>
    
    <form id="form-crear-rol">
        <div class="form-group">
            <label>Nombre del Rol:</label>
            <input type="text" name="nombre_rol" class="form-control" required placeholder="Ej: Vendedor">
        </div>
        
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción breve..."></textarea>
        </div>

        <div style="text-align:right; margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" id="btnGuardarRol">Guardar</button>
        </div>
    </form>
</div>

<script>
(function() {
    var btn = document.getElementById('btnGuardarRol');
    if(btn) {
        btn.onclick = function() {
            var f = document.getElementById('form-crear-rol');
            if(f.nombre_rol.value.trim() === "") { alert("Nombre obligatorio"); return; }
            
            var d = new FormData(f);
            fetch('server/roles_guardar.php', { method:'POST', body:d })
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