<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../server/db.php';

// Obtenemos el ID de la URL. Si no viene, mostramos un error para depurar.
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id === null) {
    echo "<p style='color:red'>Error: No se recibió el ID en la vista.</p>";
    exit;
}

$id = intval($id);
$res = $conn->query("SELECT * FROM roles WHERE id_rol = $id");
$row = $res->fetch_assoc();
?>

<div class="header-complex" style="margin-bottom: 20px;">
    <h2 style="color: var(--primary);">Editar Rol: <?php echo htmlspecialchars($row['nombre_rol']); ?></h2>
</div>

<form id="formEditarRol">
    <input type="hidden" name="id_rol" value="<?php echo $id; ?>">

    <div class="form-group">
        <label>Nombre el Rol:</label>
        <input type="text" name="nombre_rol" class="form-control" 
               value="<?php echo htmlspecialchars($row['nombre_rol']); ?>" 
               <?php echo ($_SESSION['id_usuario'] != 1) ? 'readonly' : ''; ?>>
    </div>

    <div class="form-group" style="margin-top: 15px;">
        <label>Descripción:</label>
        <textarea name="descripcion" class="form-control" rows="4" required><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
    </div>

    <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
        <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
        <button type="button" class="btn btn-success" onclick="actualizarRol()">&#128190; Guardar Cambios</button>
    </div>
</form>

<script>
    // --- SOLUCIÓN DEL PROBLEMA "NO HACE NADA" ---
    // Usamos 'window.nombreFuncion' para hacerla accesible globalmente desde el modal
    window.guardarEdicionUsuario = function() {
        console.log("Intentando guardar..."); // Para depuración en consola

        const form = document.getElementById('form-edit-user');
        
       

        const data = new FormData(form);

        // Enviar datos al servidor
        fetch('server/usuarios_update.php', { method: 'POST', body: data })
        .then(response => response.text())
        .then(texto => {
            alert(texto); // Mostrar respuesta del servidor
            if (texto.includes('correctamente') || texto.includes('Exito')) {
                cerrarModal();
                cargarVista('usuarios.php'); // Recargar la tabla
            }
        })
        .catch(err => {
            alert("Error de conexión: " + err);
        });
    };
</script>