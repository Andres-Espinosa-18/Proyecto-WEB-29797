<?php
require_once 'db.php';

// Recibir datos
$id_nota = isset($_POST['id_nota']) ? intval($_POST['id_nota']) : 0;
$n1 = isset($_POST['nota1']) ? floatval($_POST['nota1']) : 0;
$n2 = isset($_POST['nota2']) ? floatval($_POST['nota2']) : 0;
$n3 = isset($_POST['nota3']) ? floatval($_POST['nota3']) : 0;

// SOLUCIÓN AL ERROR: Validamos que si viene vacío, sea 0.
$rec = (isset($_POST['recuperacion']) && $_POST['recuperacion'] !== '') ? floatval($_POST['recuperacion']) : 0;

if ($id_nota > 0) {
    // 1. Calcular Promedio
    $calculo_prom = ($n1 + $n2 + $n3) / 3;
    $promedio = number_format($calculo_prom, 2, '.', ''); // Formato para BD

    // 2. Definir Estado (Incluyendo la lógica de Recuperación)
    $estado = 'En Proceso'; // Valor inicial
    $nota_minima = 14;

    if ($calculo_prom >= $nota_minima) {
        $estado = 'Aprobado';
    } else {
        // Si reprobó el promedio, miramos la recuperación
        if ($rec >= $nota_minima) {
            $estado = 'Aprobado'; // Pasó por recuperación
        } else {
            $estado = 'Reprobado'; // Aún no pasa
        }
    }

    // 3. Actualizar en Base de Datos
    // IMPORTANTE: Agregué promedio y estado_aprobacion al UPDATE para que se guarden
    $sql = "UPDATE notas SET 
                nota1 = $n1, 
                nota2 = $n2, 
                nota3 = $n3, 
                recuperacion = $rec, 
                promedio = $promedio, 
                estado_aprobacion = '$estado' 
            WHERE id_nota = $id_nota";

    if ($conn->query($sql)) {
        echo "ok";
    } else {
        // Esto te ayudará a ver errores si pasa algo más
        echo "Error SQL: " . $conn->error;
    }
} else {
    echo "ID de nota no válido";
}
?>