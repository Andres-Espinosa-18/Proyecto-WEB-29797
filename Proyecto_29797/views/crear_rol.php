<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once '../server/db.php';

$user_id = $_SESSION['id_usuario'] ?? 0;

function tienePermisoRol($conn, $user_id, $id_menu) {
    if ($user_id == 0) return false;
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
    $res = $conn->query($sql);
    return ($res->fetch_assoc()['total'] > 0);
}
?>

<div class="contenedor">
    <h2>Gesti√≥n de Roles</h2>
    
	       <div>
            <?php if(tienePermisoRol($conn, $user_id, 13)): ?>
                <button onclick="cargarVista('roles_crear.php')" class="btn-success">+ Nuevo Rol</button>
            <?php endif; ?>
        </div>
    <div class="tabla-controles">
 

        <div>
            <label>Mostrar: </label>
            <select id="selectorCantidadRoles" onchange="cambiarCantidadRoles()">
                <option value="5">5</option>
                <option value="10" selected>10</option>
                <option value="20">20</option>
                <option value="-1">Todos</option>
            </select>
            <span id="infoRegistrosRoles" style="font-size: 0.9em; margin-left: 10px; color: #666;"></span>
        </div>
    </div>

    <table class="tabla-gestion" id="tablaRoles">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre del Rol</th>
                <th>Descripci√≥n</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM roles ORDER BY id_rol ASC");
            while($r = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $r['id_rol']; ?></td>
                <td><strong><?php echo htmlspecialchars($r['nombre_rol']); ?></strong></td>
                <td><?php echo htmlspecialchars($r['descripcion']); ?></td>
                <td>
                    <?php if(tienePermisoRol($conn, $user_id, 14)): ?>
                        <button title="Editar" class="btn-change" onclick="cargarVista('roles_actualizar.php?id=<?php echo $r['id_rol']; ?>')">‚úèÔ∏è Editar</button>
                    <?php endif; ?>

                    <?php if(tienePermisoRol($conn, $user_id, 15)): ?>
                        <?php if ($r['id_rol'] !=1 && $r['id_rol'] !=0):  ?>
                            <button title="Eliminar" class="btn-danger" onclick="eliminarFila(<?php echo $r['id_rol']; ?>, 'rol')">üóëÔ∏è Eliminar</button>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="paginacionRoles" class="paginacion-container"></div>
</div>

<script>
    // Variables especÌficas para Roles (para no chocar con Usuarios)
    let paginaActualRoles = 1;
    let filasPorPaginaRoles = 10; 

    // Inicializar al cargar
    // Nota: Como es SPA, a veces es mejor llamar la funciÛn directamente si el script se carga din·micamente
    setTimeout(actualizarPaginacionRoles, 100); 

    function cambiarCantidadRoles() {
        const selector = document.getElementById('selectorCantidadRoles');
        filasPorPaginaRoles = parseInt(selector.value);
        paginaActualRoles = 1; 
        actualizarPaginacionRoles();
    }

    function actualizarPaginacionRoles() {
        const tabla = document.getElementById('tablaRoles');
        if(!tabla) return; // Seguridad por si la tabla aun no existe

        const cuerpo = tabla.querySelector('tbody');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const contenedorBotones = document.getElementById('paginacionRoles');
        const info = document.getElementById('infoRegistrosRoles');
        const totalFilas = filas.length;

        let limite = (filasPorPaginaRoles === -1) ? totalFilas : filasPorPaginaRoles;
        let totalPaginas = Math.ceil(totalFilas / limite);

        // 1. Mostrar/Ocultar filas
        filas.forEach((fila, index) => {
            fila.style.display = 'none';
            let inicio = (paginaActualRoles - 1) * limite;
            let fin = inicio + limite;
            if (index >= inicio && index < fin) {
                fila.style.display = '';
            }
        });

        // 2. Generar Botones
        contenedorBotones.innerHTML = '';
        
        // BotÛn Anterior
        if (totalPaginas > 1) {
            let btnPrev = document.createElement('button');
            btnPrev.innerText = '<<';
            btnPrev.className = 'paginacion-btn';
            btnPrev.onclick = () => { if(paginaActualRoles > 1) { paginaActualRoles--; actualizarPaginacionRoles(); }};
            if(paginaActualRoles === 1) btnPrev.disabled = true;
            contenedorBotones.appendChild(btnPrev);
        }

        // N˙meros
        for (let i = 1; i <= totalPaginas; i++) {
            let btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'paginacion-btn ' + (i === paginaActualRoles ? 'active' : '');
            btn.onclick = () => {
                paginaActualRoles = i;
                actualizarPaginacionRoles();
            };
            contenedorBotones.appendChild(btn);
        }

        // BotÛn Siguiente
        if (totalPaginas > 1) {
            let btnNext = document.createElement('button');
            btnNext.innerText = '>>';
            btnNext.className = 'paginacion-btn';
            btnNext.onclick = () => { if(paginaActualRoles < totalPaginas) { paginaActualRoles++; actualizarPaginacionRoles(); }};
            if(paginaActualRoles === totalPaginas) btnNext.disabled = true;
            contenedorBotones.appendChild(btnNext);
        }

   
    }
</script>