<?php
require_once 'db.php';

$id_rol = isset($_POST['id_rol']) ? intval($_POST['id_rol']) : 0;

if ($id_rol <= 0) {
    echo "<tr><td colspan='3' style='text-align:center; padding:20px;'>Seleccione un rol válido.</td></tr>";
    exit;
}

// 1. Obtener permisos actuales
$actuales = [];
$res_p = $conn->query("SELECT id_menu FROM permisos_rol WHERE id_rol = $id_rol");
while($p = $res_p->fetch_assoc()) { $actuales[] = $p['id_menu']; }

// 2. Listar menús y dibujar filas
$menus = $conn->query("SELECT * FROM menus ORDER BY id_menu ASC");

if($menus->num_rows > 0) {
    while($m = $menus->fetch_assoc()):
        $checked = in_array($m['id_menu'], $actuales) ? 'checked' : '';
        $bg = $checked ? 'background-color:#e8f5e9;' : ''; 
?>
<tr style="<?php echo $bg; ?>">
    <td style="text-align:center; color:#888;"><?php echo $m['id_menu']; ?></td>
    <td style="font-weight:500;"><?php echo htmlspecialchars($m['nombre_texto']); ?></td>
    <td style="text-align:center;">
        <input type="checkbox" name="menu_ids[]" value="<?php echo $m['id_menu']; ?>" 
               <?php echo $checked; ?> 
               style="transform: scale(1.5); cursor:pointer;">
    </td>
</tr>
<?php 
    endwhile; 
} else {
    echo "<tr><td colspan='3'>No hay menús disponibles.</td></tr>";
}
?>