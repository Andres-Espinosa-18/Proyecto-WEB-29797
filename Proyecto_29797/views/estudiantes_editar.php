<?php
require_once '../server/db.php'; // Ajusta la ruta si es necesario
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Buscamos los datos actuales del estudiante
$sql = "SELECT * FROM estudiantes WHERE id_estudiante = $id";
$res = $conn->query($sql);

if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
} else {
    echo "<p style='color:red; text-align:center;'>Estudiante no encontrado.</p>";
    exit;
}
?>

<div class="header-complex" style="border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px;">
    <div class="header-left">
        <h2 style="color: var(--primary); font-size: 1.4rem;">Editar Estudiante</h2>
        <p style="color: gray; font-size: 0.9rem; margin:0;">ID: <?php echo $id; ?></p>
    </div>
</div>

<form id="formEditarEstudiante" onsubmit="event.preventDefault(); actualizarEstudiante();">
    
    <input type="hidden" name="id_estudiante" value="<?php echo $row['id_estudiante']; ?>">
    <input type="hidden" name="accion" value="editar">

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
        
        <div class="form-group">
            <label style="font-weight:bold; font-size:0.9rem;">Nombre:</label>
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div class="form-group">
            <label style="font-weight:bold; font-size:0.9rem;">Apellido:</label>
            <input type="text" name="apellido" value="<?php echo htmlspecialchars($row['apellido']); ?>" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>

        <div class="form-group">
            <label style="font-weight:bold; font-size:0.9rem;">Cédula:</label>
            <input type="text" name="cedula" value="<?php echo htmlspecialchars($row['cedula']); ?>" required 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>
        <div class="form-group">
            <label style="font-weight:bold; font-size:0.9rem;">Usuario:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($row['usuario']); ?>" required 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>

        <div class="form-group">
            <label style="font-weight:bold; font-size:0.9rem;">Correo:</label>
            <input type="email" name="correo" value="<?php echo htmlspecialchars($row['correo']); ?>" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>
    

        <div class="form-group" style="grid-column: span 2;">
            <label style="font-weight:bold; font-size:0.9rem; color:var(--primary);">Nueva Clave (Opcional):</label>
            <input type="password" name="clave" placeholder="Dejar vacío para mantener la actual" 
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
        </div>
    </div>

    <div style="margin-top: 25px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
        <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </div>
</form>
