<?php
require_once 'db.php';
require_once 'fpdf/fpdf.php';

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, utf8_decode('Reporte de Estudiantes'), 0, 1, 'C');
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 5, 'Generado: ' . date('d/m/Y H:i'), 0, 1, 'C');
        $this->Ln(10);
        
        $this->SetFillColor(66, 139, 202); // Azul más profesional
        $this->SetTextColor(255); // Texto blanco
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(80, 8, 'Estudiante', 1, 0, 'C', true);
        $this->Cell(60, 8, 'Curso', 1, 0, 'C', true);
        $this->Cell(20, 8, 'Prom.', 1, 0, 'C', true);
        $this->Cell(30, 8, 'Estado', 1, 1, 'C', true);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Pag ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// --- CORRECCIÓN AQUÍ ---
// Usamos n.id_usuario en lugar de n.id_estudiante
$sql = "SELECT e.id_estudiante, e.nombre, e.apellido, 
               c.nombre_curso, 
               n.promedio, n.estado_aprobacion
        FROM estudiantes e
        LEFT JOIN notas n ON e.id_estudiante = n.id_usuario AND n.tipo_usuario = 'estudiante'
        LEFT JOIN cursos c ON n.id_curso = c.id_curso
        WHERE e.estado = 1
        ORDER BY e.apellido ASC, e.nombre ASC";

$res = $conn->query($sql);

// Si falla la consulta, mostramos el error exacto
if (!$res) {
    die("Error SQL: " . $conn->error);
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$actual = "";

while ($row = $res->fetch_assoc()) {
    $nombre = utf8_decode($row['apellido'] . ' ' . $row['nombre']);
    $curso = utf8_decode($row['nombre_curso'] ?? '');
    
    // Convertimos a float para formatear
    $prom_val = isset($row['promedio']) ? floatval($row['promedio']) : 0;
    $promedio = number_format($prom_val, 2);
    $estado = isset($row['estado_aprobacion']) ? $row['estado_aprobacion'] : 'Pendiente';

    // Agrupación visual (para no repetir nombres)
    if ($actual == $nombre) {
        $pdf->Cell(80, 8, '', 'LR'); 
    } else {
        if ($actual != "") $pdf->Cell(190, 0, '', 'T', 1); // Línea separadora
        $pdf->SetTextColor(0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(80, 8, $nombre, 'LRT');
        $actual = $nombre;
        $pdf->SetFont('Arial', '', 10);
    }

    // Si no tiene cursos
    if (empty($curso)) {
        $pdf->SetTextColor(100);
        $pdf->Cell(110, 8, 'Sin cursos registrados', 1, 1, 'C');
        continue;
    }

    $pdf->SetTextColor(0); // Negro para el curso
    $pdf->Cell(60, 8, $curso, 1);
    $pdf->Cell(20, 8, $promedio, 1, 0, 'C');

    // Colores para el estado
    if ($estado == 'Aprobado' || $estado == 'Aprobado (Rec.)') {
        $pdf->SetTextColor(0, 128, 0); // Verde
    } elseif ($estado == 'Reprobado') {
        $pdf->SetTextColor(200, 0, 0); // Rojo
    } else {
        $pdf->SetTextColor(255, 165, 0); // Naranja
    }

    $pdf->Cell(30, 8, utf8_decode($estado), 1, 1, 'C');
    $pdf->SetTextColor(0); // Reset color
}

$pdf->Cell(190, 0, '', 'T', 1); // Línea final
$pdf->Output('I', 'Reporte_Estudiantes.pdf');
?>