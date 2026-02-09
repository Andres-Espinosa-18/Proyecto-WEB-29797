<?php
require_once 'db.php';
require_once 'funciones_auditoria.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_nota = intval($_POST['id_nota']);
    $n1 = floatval($_POST['nota1']);
    $n2 = floatval($_POST['nota2']);
    $n3 = floatval($_POST['nota3']);
    $rec = isset($_POST['recuperacion']) ? floatval($_POST['recuperacion']) : null;

    // Calcular promedio inicial
    $promedio = ($n1 + $n2 + $n3) / 3;
    $estado = ($promedio >= 14) ? 'Aprobado' : 'Reprobado';

    // Lógica de Recuperación: Si reprobó y hay nota de recuperación
    if ($promedio < 14 && $rec !== null) {
        // El nuevo promedio suele ser (Promedio Anterior + Recuperación) / 2 
        // o según tu criterio. Aquí recalculamos:
        $promedioFinal = ($promedio + $rec) / 2;
        $estado = ($promedioFinal >= 14) ? 'Aprobado' : 'Reprobado';
        $promedio = $promedioFinal;
    }

    $stmt = $conn->prepare("UPDATE notas SET nota1=?, nota2=?, nota3=?, recuperacion=?, promedio=?, estado_aprobacion=? WHERE id_nota=?");
    $stmt->bind_param("ddddssi", $n1, $n2, $n3, $rec, $promedio, $estado, $id_nota);

    if ($stmt->execute()) {
        registrarEvento($conn, "Actualizó notas ID: $id_nota - Estado: $estado");
        echo "Notas actualizadas correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}