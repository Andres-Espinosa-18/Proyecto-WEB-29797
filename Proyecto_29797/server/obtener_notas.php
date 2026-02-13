<?php
require_once 'db.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta de notas (La misma lógica que querías)
$sqlNotas = "SELECT n.id_nota, n.id_curso, n.nota1, n.nota2, n.nota3, n.recuperacion,
                    c.nombre_curso
             FROM notas n
             INNER JOIN cursos c ON n.id_curso = c.id_curso
             WHERE n.id_estudiante = $id";
$resNotas = $conn->query($sqlNotas);

if ($resNotas->num_rows == 0) {
    echo "<tr><td colspan='8' class='text-center'>Este estudiante no está inscrito en ningún curso.</td></tr>";
    exit;
}

while($row = $resNotas->fetch_assoc()): 
    $n1 = floatval($row['nota1']);
    $n2 = floatval($row['nota2']);
    $n3 = floatval($row['nota3']);
    $rec = floatval($row['recuperacion']);
    
    // Cálculos
    $prom = ($n1 + $n2 + $n3) / 3;
    
    // Lógica de Estado y Recuperación
    $estado = "En Proceso";
    $clase = "etiqueta-proceso"; 
    $necesitaRecuperacion = false;

    if ($prom >= 14) {
        $estado = "Aprobado";
        $clase = "etiqueta-activo"; 
    } else {
        $necesitaRecuperacion = true;
        if ($rec >= 14) {
            $estado = "Aprobado (Rec.)";
            $clase = "etiqueta-activo";
        } elseif ($rec > 0) {
            $estado = "Reprobado";
            $clase = "etiqueta-inactivo";
        } else {
            $estado = "Supletorio";
            $clase = "etiqueta-inactivo"; 
        }
    }
?>
    <tr>
        <td style="font-weight:bold;"><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
        
        <td><input type="number" step="0.01" max="20" class="form-control text-center" id="n1_<?php echo $row['id_curso']; ?>" value="<?php echo $n1; ?>"></td>
        <td><input type="number" step="0.01" max="20" class="form-control text-center" id="n2_<?php echo $row['id_curso']; ?>" value="<?php echo $n2; ?>"></td>
        <td><input type="number" step="0.01" max="20" class="form-control text-center" id="n3_<?php echo $row['id_curso']; ?>" value="<?php echo $n3; ?>"></td>
        
        <td class="text-center"><b><?php echo number_format($prom, 2); ?></b></td>

        <td class="text-center">
            <?php if($necesitaRecuperacion): ?>
                <input type="number" step="0.01" max="20" class="form-control text-center" 
                       style="border: 1px solid red;"
                       id="rec_<?php echo $row['id_curso']; ?>" 
                       value="<?php echo ($rec > 0) ? $rec : ''; ?>" 
                       placeholder="Nota">
            <?php else: ?>
                <span style="color:#ccc;">--</span>
                <input type="hidden" id="rec_<?php echo $row['id_curso']; ?>" value="0">
            <?php endif; ?>
        </td>

        <td class="text-center">
            <span class="<?php echo $clase; ?>" style="padding: 5px 10px; border-radius: 15px; font-size: 0.85rem; display:inline-block; width:100%;">
                <?php echo $estado; ?>
            </span>
        </td>

        <td class="acciones-col">
            <button class="btn btn-primary" onclick="guardarNotaFila(<?php echo $row['id_curso']; ?>)">
                &#128190; Guardar
            </button>
        </td>
    </tr>
<?php endwhile; ?>