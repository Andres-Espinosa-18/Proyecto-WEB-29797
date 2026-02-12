<?php
require_once '../server/db.php';
$id = intval($_GET['id'] ?? 0);

// Buscamos la nota y también el nombre del curso y del estudiante para mostrar contexto
// NOTA: 'id_usuario' en la tabla notas contiene el ID del estudiante
$sql = "SELECT n.*, c.nombre_curso 
        FROM notas n 
        JOIN cursos c ON n.id_curso = c.id_curso
        WHERE n.id_nota = $id";
$res = $conn->query($sql);
$n = $res->fetch_assoc();

if(!$n) { echo "Nota no encontrada"; exit; }
?>

<div class="contenedor">
    <div style="background: #f8f9fa; padding: 15px; border-left: 5px solid #3498db; margin-bottom: 20px;">
        <h3>Editar Calificaciones</h3>
        <p><strong>Curso:</strong> <?php echo htmlspecialchars($n['nombre_curso']); ?></p>
    </div>

    <form id="form-notas">
        <input type="hidden" name="id_nota" value="<?php echo $id; ?>">
        
        <input type="hidden" id="id_estudiante_redirect" value="<?php echo $n['id_usuario']; ?>">
        
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
            <div>
                <label>Nota 1:</label> 
                <input type="number" name="nota1" class="form-control" value="<?php echo $n['nota1']; ?>" max="20" min="0" oninput="validarNota(this)">
            </div>
            <div>
                <label>Nota 2:</label> 
                <input type="number" name="nota2" class="form-control" value="<?php echo $n['nota2']; ?>" max="20" min="0" oninput="validarNota(this)">
            </div>
            <div>
                <label>Nota 3:</label> 
                <input type="number" name="nota3" class="form-control" value="<?php echo $n['nota3']; ?>" max="20" min="0" oninput="validarNota(this)">
            </div>
        </div>
        
        <?php if($n['promedio'] < 14 && $n['promedio'] > 0): ?>
        <div style="background:#fff3cd; padding:10px; margin-top:15px; border: 1px solid #ffeeba; border-radius: 5px;">
            <label style="color: #856404; font-weight: bold;">Nota de Recuperación:</label>
            <input type="number" name="recuperacion" class="form-control" value="<?php echo $n['recuperacion']; ?>" max="20" min="0" oninput="validarNota(this)">
            <small>El estudiante reprobó. Ingrese nota para promediar con el anterior.</small>
        </div>
        <?php endif; ?>

        <div style="margin-top:20px; display: flex; gap: 10px;">
            <button type="button" onclick="guardarNotas()" class="btn-success">?? Guardar Cambios</button>
            
            <button type="button" class="btn-back" 
                    onclick="cargarVista('calificaciones.php?id_estudiante=<?php echo $n['id_usuario']; ?>')">
                Cancelar
            </button>
        </div>
    </form>
</div>

<style>
    .form-control { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 1.1em; }
</style>

<script>
function guardarNotas() {
    const datos = new FormData(document.getElementById('form-notas'));
    
    // Capturamos el ID del estudiante desde el campo oculto
    const idEstudiante = document.getElementById('id_estudiante_redirect').value;

    fetch('server/notas_update.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(d => { 
        alert(d); 
        // REDIRECCIÓN INTELIGENTE:
        // Volvemos a la vista de calificaciones PERO filtrada por el estudiante
        cargarVista('calificaciones.php?id_estudiante=' + idEstudiante); 
    });
}
	
function validarNota(input) {
    let valor = parseFloat(input.value);
    if (valor < 0) input.value = 0;
    if (valor > 20) input.value = 20;
}
</script>