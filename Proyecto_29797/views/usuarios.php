<?php
require_once '../server/db.php';

// Filtros
$estado = $_GET['estado'] ?? 'todos'; 
$where = "WHERE 1=1";
if($estado == 'activos') $where .= " AND u.estado = 1";
if($estado == 'inactivos') $where .= " AND u.estado = 0";

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
                    <option value="nombre">Nombre</option>
                </select>
                <input type="text" id="terminoBusqueda" placeholder="Buscar..." onkeyup="if(event.key === 'Enter') buscarUsuarios()">
                <button class="btn-change" onclick="buscarUsuarios()">&#128269;</button>
				
				<select id="limiteRegistros" onchange="buscarUsuarios(1)" 
					style="margin-left: 20px; border: 1px solid #ccc; border-radius: 4px; width:auto; font-weight:bold; cursor:pointer; padding: 5px;">
				<option value="5">Ver 5</option>
				<option value="10" selected>Ver 10</option>
				<option value="20">Ver 20</option>
				<option value="50">Ver 50</option>
			</select>
            </div>
        </div>

					<div class="header-right no-print">
				<div class="btn-group-top">
					<button class="btn-success" onclick="abrirModal('usuarios_crear.php')">+ Nuevo</button>
					<button class="btn-change" onclick="window.print()">&#128462; PDF</button>
				</div>

				<div class="filtros-container" style="margin-top: 10px;">
					<label>Estado:</label>
					<select id="filtroEstado" onchange="buscarUsuarios(1)">
						<option value="todos">Todos</option>
						<option value="activos">Activos</option>
						<option value="inactivos">Inactivos</option>
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
                <th style="text-align:center;">Estado</th>
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
                
                <td style="text-align:center;">
                    <?php if($row['estado']==1): ?>
                        <span style="color:#27ae60; font-weight:bold;">Activo</span>
                    <?php else: ?>
                        <span style="color:#c0392b; font-weight:bold;">Inactivo</span>
                    <?php endif; ?>
                </td>
                
                <td class="acciones-col no-print">
                    <button class="btn-change" onclick="abrirModal('usuarios_actualizar.php?id=<?php echo $row['id_usuario']; ?>')">
                        &#9998; Editar
                    </button>

                    <?php if($es_admin): ?>
                        <button class="btn-disabled" onclick="alert('No puedes modificar al Administrador')">&#10006; Eliminar</button>
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
// La función ahora acepta el parámetro 'pagina'
function buscarUsuarios(pagina = 1) {
    const criterio = document.getElementById('criterioBusqueda').value;
    const termino = document.getElementById('terminoBusqueda').value;
    
    // Obtenemos el límite del select nuevo (o ponemos 10 por defecto si no existe)
    let limiteSelect = document.getElementById('limiteRegistros');
    const limite = limiteSelect ? limiteSelect.value : 10;
    
    // Filtro de estado (del select de la derecha)
    // Asegúrate de que tu select de estado tenga id="filtroEstado" o busca el querySelector
    // Si usas el código anterior, puede que sea 'select-bottom select'
    let selectEstado = document.querySelector('.select-bottom select');
    const estado = selectEstado ? selectEstado.value : 'todos';

    const d = new FormData();
    d.append('tipo', 'usuario_avanzado');
    d.append('criterio', criterio);
    d.append('termino', termino);
    
    // Enviamos los datos de paginación
    d.append('pagina', pagina);
    d.append('limite', limite);
    d.append('estado', estado);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        // Simple y directo: Pegamos el HTML (filas + botones) en la tabla
        document.querySelector('#tablaUsuariosResultados tbody').innerHTML = html;
    })
    .catch(err => alert("Error cargando: " + err));
}

// Cargar al inicio
document.addEventListener("DOMContentLoaded", function() {
    buscarUsuarios(1);
});

// --- FUNCIÓN ACTUALIZADA PARA USAR TUS ARCHIVOS GENERALES ---
function cambiarEstadoUsuario(id, nuevoEstado) {
    let accion = (nuevoEstado === 1) ? "ACTIVAR" : "INACTIVAR";
    if(!confirm("Seguro de " + accion + " este usuario")) return;
    
    // Decidimos a qué archivo llamar
    let archivo = (nuevoEstado === 1) ? 'server/activar_general.php' : 'server/eliminar_general.php';
    
    const d = new FormData(); 
    d.append('id', id); 
    d.append('tipo', 'usuario'); // Importante para tu archivo general
    
    fetch(archivo, { method:'POST', body:d })
    .then(r => r.text())
    .then(msg => {
        // alert(msg); // Opcional
        cargarVista('usuarios.php');
    })
    .catch(err => alert("Error: " + err));
}
</script>