<?php
require_once '../server/db.php';
$id = $_GET['id'];
$res = $conn->query("SELECT * FROM notas WHERE id_nota = $id");
$n = $res->fetch_assoc();
?>
<div class="contenedor">
    <h3>Ingreso de Calificaciones (0 - 20)</h3>
    <form id="form-notas">
        <input type="hidden" name="id_nota" value="<?php echo $id; ?>">
        <label>Nota 1:</label> <input type="number" name="nota1" value="<?php echo $n['nota1']; ?>" max="20" min="0" oninput="validarNota(this)">
        <label>Nota 2:</label> <input type="number" name="nota2" value="<?php echo $n['nota2']; ?>" max="20" min="0" oninput="validarNota(this)">
        <label>Nota 3:</label> <input type="number" name="nota3" value="<?php echo $n['nota3']; ?>" max="20" min="0" oninput="validarNota(this)">
        
        <?php if($n['promedio'] < 14 && $n['promedio'] > 0): ?>
        <div style="background:#f8d7da; padding:10px; margin-top:10px;">
            <label>Nota de RecuperaciÃ³n:</label>
            <input type="number" name="recuperacion" value="<?php echo $n['recuperacion']; ?>" max="20" min="0" oninput="validarNota(this)">
        </div>
        <?php endif; ?>
		<br>
        <button type="button" onclick="guardarNotas()" class="btn-success" style="margin-top:10px;">ðŸ’¾ Guardar Calificaciones</button>
    </form>
</div>

<script>
function guardarNotas() {
    const datos = new FormData(document.getElementById('form-notas'));
    fetch('server/notas_update.php', { method: 'POST', body: datos })
    .then(res => res.text())
    .then(d => { alert(d); cargarVista('calificaciones.php'); });
}
	
function validarNota(input) {
    let valor = input.value;

    // 1. Evitar números negativos o caracteres no numéricos
    if (valor < 0) {
        input.value = 0;
    }

    // 2. Si el valor supera 20, forzarlo a 20 o vaciarlo
    if (valor > 20) {
        alert("La nota maxima es 20");
        input.value = ""; // O puedes poner input.value = 20;
    }

    // 3. Limitar a dos decimales si es necesario
    if (valor.includes('.')) {
        let partes = valor.split('.');
        if (partes[1].length > 2) {
            input.value = parseFloat(valor).toFixed(2);
        }
    }
}
</script>