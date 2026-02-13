<div style="padding:10px;">
    <h3 style="margin-top:0;">Nuevo Usuario</h3>
    <form id="form-crear-user">
        <div class="form-group">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Apellido:</label>
            <input type="text" name="apellido" class="form-control" >
        </div>

        <div class="form-group">
            <label>Cédula:</label>
            <input type="text" name="cedula" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Fecha Nacimiento:</label>
            <input type="date" name="fecha_nacimiento" class="form-control" max="2008-02-13" required>
        </div>

        <div class="form-group">
            <label>Usuario:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Contraseña:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Rol:</label>
            <select name="id_rol" class="form-control">
                <?php
                require_once '../server/db.php';
                $r = $conn->query("SELECT * FROM roles");
                while($row=$r->fetch_assoc()) echo "<option value='{$row['id_rol']}'>{$row['nombre_rol']}</option>";
                ?>
            </select>
        </div>

        <div style="text-align:right; margin-top:15px;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" id="btnGuardarNuevo">Guardar</button>
        </div>
    </form>
</div>

<script>
(function() {
    // Buscamos el botón por su ID
    var btn = document.getElementById('btnGuardarNuevo');
    
    if (btn) {
        btn.onclick = function() {
            var f = document.getElementById('form-crear-user');
            
            // Validación básica HTML5
            if (!f.checkValidity()) {
                // Esto fuerza al navegador a mostrar qué campo falta
                f.reportValidity(); 
                return; 
            }

            var d = new FormData(f);

            fetch('server/usuarios_guardar.php', { method:'POST', body:d })
            .then(function(r) { return r.text(); })
            .then(function(m) { 
                alert(m); 
                // Si el mensaje del servidor dice "guardado"
                if(m.indexOf('guardado') !== -1) {
                    cerrarModal(); 
                    cargarVista('usuarios.php'); 
                }
            })
            .catch(function(e) { alert("Error: " + e); });
        };
    }
})();
</script>