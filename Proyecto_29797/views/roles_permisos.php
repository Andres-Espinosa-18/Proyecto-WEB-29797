<?php
require_once '../server/db.php';
$id_rol = $_GET['id_rol'] ?? '';
?>

<div class="contenedor">
    <h2>Administrador de Permisos</h2>
    
    <select onchange="cargarVista('roles_permisos.php?id_rol=' + this.value)">
        <option value="">-- Seleccionar Rol --</option>
        <?php
        $roles = $conn->query("SELECT * FROM roles WHERE id_rol != 1");
        while($r = $roles->fetch_assoc()) {
            $sel = ($id_rol == $r['id_rol']) ? 'selected' : '';
            echo "<option value='{$r['id_rol']}' $sel>{$r['nombre_rol']}</option>";
        }
        ?>
    </select>

    <?php if ($id_rol != ''): ?>
    <form id="form-permisos">
        <input type="hidden" name="id_rol" value="<?php echo $id_rol; ?>">
        <table class="tabla-gestion">
            <thead>
                <tr><th>Menú</th><th>Permitir</th></tr>
            </thead>
            <tbody>
                <?php
                $actuales = [];
                $res_p = $conn->query("SELECT id_menu FROM permisos_rol WHERE id_rol = $id_rol");
                while($p = $res_p->fetch_assoc()) { $actuales[] = $p['id_menu']; }

                $menus = $conn->query("SELECT * FROM menus ORDER BY id_menu ASC");
                while($m = $menus->fetch_assoc()):
                ?>
                <tr>
                    <td><?php echo $m['nombre_texto']; ?></td>
                    <td>
                        <input type="checkbox" name="menu_ids[]" value="<?php echo $m['id_menu']; ?>" 
                        <?php echo in_array($m['id_menu'], $actuales) ? 'checked' : ''; ?>>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <button type="submit" onclick="ejecutarGuardadoPermisos()" class="btn-success">Guardar Cambios</button>
    </form>
    <?php endif; ?>
</div>

<script>
// Definimos la función en el objeto window para que sea global y la SPA no la pierda
window.ejecutarGuardadoPermisos = function() {
    const form = document.getElementById('form-permisos');
    const datos = new FormData(form);

    fetch('server/actualizar_permisos.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.text())
    .then(res => {
        if(res.trim() === "OK_GUARDADO") {
            alert("Permisos actualizados correctamente");
            // Forzamos recarga del menú para ver cambios (opcional)
            location.reload(); 
        } else {
            alert("Respuesta del servidor: " + res);
        }
    })
    .catch(err => alert("Error de conexión: " + err));
}
</script>