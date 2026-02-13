<?php
require_once '../server/db.php';

// Filtro de Estado
$estado = $_GET['estado'] ?? 'todos'; 
$where = "WHERE 1=1";
if($estado == 'activos') $where .= " AND u.estado = 1";
if($estado == 'inactivos') $where .= " AND u.estado = 0";

// Ordenamiento
$sql = "SELECT u.*, r.nombre_rol, r.id_rol FROM usuarios u 
        LEFT JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario 
        LEFT JOIN roles r ON ur.id_rol = r.id_rol
        $where 
        ORDER BY u.apellido ASC";
?>

<div class="contenedor">
    <div class="header-complex">
        
        <div class="header-left">
            <h2>Gesti&oacute;n de Usuarios</h2>
            
            <div class="search-bar-advanced no-print">
                <select id="criterioBusqueda">
                    <option value="general">Todo</option>
                    <option value="cedula">C&eacute;dula</option>
                    <option value="username">Usuario</option>
                    <option value="nombre">Nombre/Apellido</option>
                    <option value="email">Email</option>
                </select>
                <input type="text" id="terminoBusqueda" placeholder="Buscar..." 
                       onkeyup="if(event.key === 'Enter') buscarUsuarios()">
                <button class="btn-change" onclick="buscarUsuarios()">&#128269;</button>
            </div>
        </div>

        <div class="header-right no-print">
            <div class="btn-group-top">
                <button class="btn-success" onclick="abrirModal('usuarios_crear.php')">&#10010; Nuevo</button>
                <button class="btn-change" onclick="window.print()">&#128462; PDF</button>
            </div>
            <div class="select-bottom">
                <select onchange="cargarVista('usuarios.php?estado='+this.value)" class="form-control-sm" style="text-align:center; text-align-last:center;">
                    <option value="todos" <?php echo $estado=='todos'?'selected':''; ?>>Estado: Todos</option>
                    <option value="activos" <?php echo $estado=='activos'?'selected':''; ?>>Estado: Activos</option>
                    <option value="inactivos" <?php echo $estado=='inactivos'?'selected':''; ?>>Estado: Inactivos</option>
                </select>
            </div>
        </div>

    </div>

    <table class="tabla-gestion" id="tablaUsuariosResultados">
        <thead>
            <tr>
                <th>Apellidos y Nombres</th>
                <th>C&eacute;dula</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th class="acciones-col no-print">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $res = $conn->query($sql);
            if ($res && $res->num_rows > 0):
                while($row = $res->fetch_assoc()): 
                    $es_admin = ($row['id_rol'] == 1);
            ?>
            <tr>
                <td style="text-align:left;"><?php echo htmlspecialchars($row['apellido'] . ' ' . $row['nombre']); ?></td>
                <td><?php echo htmlspecialchars($row['cedula']); ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['nombre_rol']); ?></td>
                <td>
                    <?php if($row['estado']==1): ?>
                        <span style="color:var(--success); font-weight:bold;">Activo</span>
                    <?php else: ?>
                        <span style="color:var(--danger); font-weight:bold;">Inactivo</span>
                    <?php endif; ?>
                </td>
                <td class="acciones-col no-print">
                    <button class="btn-change" onclick="abrirModal('usuarios_actualizar.php?id=<?php echo $row['id_usuario']; ?>')">
                        &#9998; Editar
                    </button>

                    <?php if($es_admin): ?>
                        <button class="btn-disabled" onclick="alert('No puedes eliminar al Administrador Principal')">
                            &#10006; Eliminar
                        </button>
                    <?php else: ?>
                        <?php if($row['estado']==1): ?>
                            <button class="btn-danger" onclick="cambiarEstadoUsuario(<?php echo $row['id_usuario']; ?>, 0)">
                                &#10006; Eliminar
                            </button>
                        <?php else: ?>
                            <button class="btn-success" onclick="cambiarEstadoUsuario(<?php echo $row['id_usuario']; ?>, 1)">
                                &#10004; Activar
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; 
            else: ?>
                <tr><td colspan="6">No hay usuarios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function buscarUsuarios() {
    const criterio = document.getElementById('criterioBusqueda').value;
    const termino = document.getElementById('terminoBusqueda').value;
    
    const d = new FormData();
    d.append('tipo', 'usuario_avanzado');
    d.append('criterio', criterio);
    d.append('termino', termino);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        document.querySelector('#tablaUsuariosResultados tbody').innerHTML = html;
    });
}

function cambiarEstadoUsuario(id, nuevoEstado) {
    let accion = (nuevoEstado === 1) ? "ACTIVAR" : "ELIMINAR (Inactivar)";
    if(!confirm("¿Seguro de " + accion + " este usuario?")) return;
    
    const d = new FormData(); 
    d.append('id', id); 
    d.append('estado', nuevoEstado); // Enviamos explícitamente el nuevo estado
    
    fetch('server/eliminar_usuario.php', { method:'POST', body:d })
    .then(r => r.text())
    .then(msg => {
        // alert(msg); // Opcional: mostrar mensaje del servidor
        cargarVista('usuarios.php'); // Recarga SPA sin refrescar navegador
    });
}
</script>