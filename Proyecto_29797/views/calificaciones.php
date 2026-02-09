<?php
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$user_id = $_SESSION['id_usuario'] ?? 0;
$id_rol_sesion = 0;


$res_rol = $conn->query("SELECT id_rol FROM usuario_roles WHERE id_usuario = $user_id");
if($row_r = $res_rol->fetch_assoc()){
    $id_rol_sesion = $row_r['id_rol'];
}

// Lógica de Filtro:
// Si es Admin (1), ve todo. Si no, solo ve lo suyo.
$where_clause = ($id_rol_sesion == 1) ? "" : " WHERE n.id_usuario = $user_id";
?>

<div class="contenedor">
    <h2><?php echo ($id_rol_sesion == 1) ? "Panel de Control de Notas" : "Mis Calificaciones"; ?></h2>
    
    <table class="tabla-gestion" id="tablaNotas">
        <thead>
            <tr>
                <?php if($id_rol_sesion == 1): ?> <th>Estudiante</th> <?php endif; ?>
                <th>Curso</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Recup.</th>
                <th>Promedio</th>
                <th>Estado</th>
                <?php if($id_rol_sesion == 1): ?> <th>Acciones</th> <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT n.*, u.nombre_real, c.nombre_curso 
                    FROM notas n 
                    JOIN usuarios u ON n.id_usuario = u.id_usuario 
                    JOIN cursos c ON n.id_curso = c.id_curso
                    $where_clause";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <?php if($id_rol_sesion == 1): ?> <td><?php echo htmlspecialchars($row['nombre_real']); ?></td> <?php endif; ?>
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
                <?php if($id_rol_sesion == 1): ?>
                <td>
                    <button class="btn-change" onclick="cargarVista('notas_editar.php?id=<?php echo $row['id_nota']; ?>')">✏️ Editar</button>
                </td>
                <?php endif; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>