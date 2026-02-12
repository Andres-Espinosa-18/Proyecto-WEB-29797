<?php
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$busqueda = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';
$where = "";
if($busqueda != '') {
    $where = "WHERE nombre LIKE '%$busqueda%' OR cedula LIKE '%$busqueda%'";
}
?>

<div class="contenedor">
    <h2>Gestión de Estudiantes</h2>
    
    <div style="display:flex; justify-content:space-between; margin-bottom:20px;">
        <input type="text" placeholder="Buscar por nombre o cédula..." 
               value="<?php echo $busqueda; ?>"
               onkeyup="if(event.key === 'Enter') cargarVista('estudiantes.php?q='+this.value)"
               style="padding:10px; width:300px; border:1px solid #ccc; border-radius:5px;">
        
        <button class="btn-success" onclick="cargarVista('estudiantes_crear.php')">Nuevo Estudiante</button>
    </div>

    <table class="tabla-gestion">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Carrera</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM estudiantes $where ORDER BY nombre ASC";
            $res = $conn->query($sql);
            
            if ($res && $res->num_rows > 0) {
                while($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                <td><?php echo htmlspecialchars($row['carrera']); ?></td>
                <td>
                    <?php if($row['estado'] == 1): ?>
                        <span class="badge bg-success">Activo</span>
                    <?php else: ?>
                        <span class="badge bg-danger">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td style="display:flex; gap:5px;">
                    <button class="btn-change" style="background-color:#2980b9; font-size: 0.8rem;" title="Ver Cursos y Notas"
                            onclick="cargarVista('calificaciones.php?id_estudiante=<?php echo $row['id_estudiante']; ?>')">
                        Notas
                    </button>
                    
                    <button class="btn-change" style="font-size: 0.8rem;"
                            onclick="cargarVista('estudiantes_editar.php?id=<?php echo $row['id_estudiante']; ?>')">
                        Editar
                    </button>

                    <?php if($row['estado'] == 1): ?>
                        <button class="btn-danger" style="font-size: 0.8rem;"
                                onclick="cambiarEstadoEstudiante(<?php echo $row['id_estudiante']; ?>, 0)">
                            Eliminar
                        </button>
                    <?php else: ?>
                        <button class="btn-success" style="font-size: 0.8rem;"
                                onclick="cambiarEstadoEstudiante(<?php echo $row['id_estudiante']; ?>, 1)">
                            Activar
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php 
                endwhile;
            } else {
                echo "<tr><td colspan='5' style='text-align:center;'>No se encontraron estudiantes.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function cambiarEstadoEstudiante(id, nuevoEstado) {
    let accion = (nuevoEstado === 1) ? "ACTIVAR" : "ELIMINAR (Inactivar)";
    if(confirm("¿Estás seguro de " + accion + " a este estudiante?")) {
        const d = new FormData();
        d.append('accion', 'cambiar_estado');
        d.append('id', id);
        d.append('estado', nuevoEstado);
        
        fetch('server/estudiantes_acciones.php', { method: 'POST', body: d })
        .then(r => r.text())
        .then(msg => {
            alert(msg);
            cargarVista('estudiantes.php');
        });
    }
}
</script>