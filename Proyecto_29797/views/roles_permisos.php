<div class="contenedor">
    <div class="header-complex">
        <div class="header-left">
            <h2>Administrar Permisos</h2>
            <div class="search-bar-advanced no-print">
                <select id="selectRolPermisos" onchange="cargarTablaPermisos(this.value)" 
                        style="min-width: 200px; font-weight:bold;">
                    <option value="0">-- Seleccione un Rol --</option>
                    <?php
                    require_once '../server/db.php';
                    $roles = $conn->query("SELECT * FROM roles WHERE id_rol > 1");
                    while($r = $roles->fetch_assoc()) {
                        echo "<option value='{$r['id_rol']}'>{$r['nombre_rol']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="header-right no-print">
            <div class="btn-group-top">
                <button class="btn-success" onclick="guardarPermisos()" id="btnGuardarPermisos" disabled>&#128190; Guardar Cambios</button>
            </div>
        </div>
    </div>

    <div style="margin-top: 15px;">
        <form id="form-permisos">
            <input type="hidden" name="id_rol" id="hidden_id_rol" value="0">
            
            <table class="tabla-gestion">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align:center;">ID</th>
                        <th>Módulo / Menú</th>
                        <th style="width: 100px; text-align:center;">Acceso</th>
                    </tr>
                </thead>
                <tbody id="tbody-permisos">
                    <tr><td colspan="3" style="text-align:center; padding:30px; color:#666;">
                        &#11013; Seleccione un rol arriba para configurar.
                    </td></tr>
                </tbody>
            </table>
        </form>
    </div>
</div>

<script>
function cargarTablaPermisos(idRol) {
    const btn = document.getElementById('btnGuardarPermisos');
    const inputHidden = document.getElementById('hidden_id_rol');
    const tbody = document.getElementById('tbody-permisos');

    if(idRol == "0") {
        btn.disabled = true;
        btn.className = "btn-disabled";
        tbody.innerHTML = "<tr><td colspan='3' style='text-align:center; padding:30px; color:#666;'>Seleccione un rol.</td></tr>";
        return;
    }

    // Activamos interfaz
    btn.disabled = false;
    btn.className = "btn-success";
    inputHidden.value = idRol;
    tbody.innerHTML = "<tr><td colspan='3' style='text-align:center;'>Cargando permisos...</td></tr>";

    // Pedimos la tabla al servidor (AJAX)
    const d = new FormData();
    d.append('id_rol', idRol);

    fetch('server/obtener_permisos_tabla.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        tbody.innerHTML = html;
    })
    .catch(err => alert("Error cargando tabla: " + err));
}

function guardarPermisos() {
    if(!confirm("¿Desea guardar la configuración?")) return;

    const form = document.getElementById('form-permisos');
    const d = new FormData(form);

    fetch('server/actualizar_permisos.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(res => {
        if(res.trim() === "OK") {
            alert("Permisos actualizados correctamente.");
            // Recargamos la tabla para confirmar visualmente (opcional)
            cargarTablaPermisos(document.getElementById('selectRolPermisos').value);
        } 
    })
    .catch(err => alert("Error de red: " + err));
}
</script>