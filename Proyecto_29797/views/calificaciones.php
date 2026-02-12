<?php
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$rol_sistema = $_SESSION['rol_sistema'] ?? 'invitado';
$user_id = $_SESSION['id_usuario'] ?? 0;

// Lógica de Filtro
$filtro_sql = "";
$titulo = "Panel de Notas";
$modo_edicion = false; // Solo el admin puede editar

if ($rol_sistema === 'estudiante') {
    // Caso 1: Estudiante viendo sus propias notas
    $filtro_sql = "WHERE n.id_usuario = $user_id AND n.tipo_usuario = 'estudiante'";
    $titulo = "Mis Calificaciones";
} 
elseif ($rol_sistema === 'administrativo') {
    $modo_edicion = true;
    
    // Caso 2: Admin viendo notas de un estudiante específico (Viene del botón de la tabla estudiantes)
    if (isset($_GET['id_estudiante'])) {
        $target_id = intval($_GET['id_estudiante']);
        $filtro_sql = "WHERE n.id_usuario = $target_id AND n.tipo_usuario = 'estudiante'";
        
        // Obtener nombre para el título
        $res_nom = $conn->query("SELECT nombre FROM estudiantes WHERE id_estudiante = $target_id");
        $nom = $res_nom->fetch_assoc()['nombre'] ?? 'Estudiante';
        $titulo = "Notas de: " . $nom;
        
    } else {
        // Caso 3: Admin viendo todo (Opcional, si entra directo al menú podría estar vacío o ver todo)
        // Por defecto mostramos todo ordenado por curso
        $filtro_sql = ""; 
    }
}
?>

<div class="contenedor">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2><?php echo htmlspecialchars($titulo); ?></h2>
        <?php if(isset($_GET['id_estudiante'])): ?>
            <button class="btn-back" onclick="cargarVista('estudiantes.php')">⬅ Volver a Lista</button>
        <?php endif; ?>
    </div>
    
    <table class="tabla-gestion" id="tablaNotas">
        <thead>
            <tr>
                <?php if($filtro_sql == ""): ?> <th>Estudiante</th> <?php endif; ?>
                <th>Curso</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Recup.</th>
                <th>Promedio</th>
                <th>Estado</th>
                <?php if($modo_edicion): ?> <th>Acciones</th> <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT n.*, c.nombre_curso, 
                    COALESCE(u.nombre_real, e.nombre, 'Desconocido') as nombre_persona
                    FROM notas n 
                    JOIN cursos c ON n.id_curso = c.id_curso
                    LEFT JOIN usuarios u ON n.id_usuario = u.id_usuario AND n.tipo_usuario = 'usuario'
                    LEFT JOIN estudiantes e ON n.id_usuario = e.id_estudiante AND n.tipo_usuario = 'estudiante'
                    $filtro_sql
                    ORDER BY c.nombre_curso ASC";

            $res = $conn->query($sql);
            
            if ($res && $res->num_rows > 0) {
                while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <?php if($filtro_sql == ""): ?> 
                    <td><?php echo htmlspecialchars($row['nombre_persona']); ?></td> 
                <?php endif; ?>
                
                <td><?php echo htmlspecialchars($row['nombre_curso']); ?></td>
                <td><?php echo $row['nota1']; ?></td>
                <td><?php echo $row['nota2']; ?></td>
                <td><?php echo $row['nota3']; ?></td>
                <td><?php echo ($row['recuperacion'] !== null) ? $row['recuperacion'] : '-'; ?></td>
                <td style="font-weight:bold;"><?php echo $row['promedio']; ?></td>
                <td>
                    <span class="badge <?php echo ($row['estado_aprobacion'] == 'Aprobado') ? 'bg-success' : 'bg-danger'; ?>">
                        <?php echo $row['estado_aprobacion']; ?>
                    </span>
                </td>
                
                <?php if($modo_edicion): ?>
                <td>
                    <button class="btn-change" onclick="cargarVista('notas_editar.php?id=<?php echo $row['id_nota']; ?>')">✏️ Editar</button>
                </td>
                <?php endif; ?>
            </tr>
            <?php 
                endwhile; 
            } else {
                echo "<tr><td colspan='8' style='text-align:center;'>No hay registros de notas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>