<?php
// 1. Incluimos la base de datos (ruta relativa desde /views hacia /server)
require_once '../server/db.php';

// Iniciamos sesi√≥n si no est√° iniciada (necesaria para el user_id)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['id_usuario'] ?? 0;

// 2. DEFINIMOS LA FUNCI√ìN (Fundamental para que no d√© error)
function tienePermiso($conn, $user_id, $id_menu) {
    // Si no hay usuario, no tiene permiso
    if ($user_id == 0) return false;
    
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
            
    $res = $conn->query($sql);
    if ($res) {
        $row = $res->fetch_assoc();
        return $row['total'] > 0;
    }
    return false;
}
?>

<div class="contenedor">
    <h2>Gesti√≥n de Usuarios</h2>
    
    <?php if(tienePermiso($conn, $user_id, 10)): ?>
        <button onclick="cargarVista('usuarios_crear.php')" class="btn-success">+ Nuevo Usuario</button>
    <?php endif; ?>

   <div class="tabla-controles">
    <div>
        <label style="color: #4a5568; font-weight: bold;">Mostrar: </label>
        <select id="selectorCantidad" onchange="cambiarCantidad()">
            <option value="5">5 registros</option>
            <option value="10" selected>10 registros</option>
            <option value="20">20 registros</option>
            <option value="-1">Todos</option>
        </select>
    </div>
    <div style="color: #718096; font-size: 0.9em;" id="infoRegistros"></div>
</div>

<table class="tabla-gestion" id="tablaUsuarios">
    <thead>
        <tr>
            <th>Username</th>
            <th>Nombre del Usuario</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $res = $conn->query("SELECT * FROM usuarios");
        if ($res):
            while($u = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo htmlspecialchars($u['username']); ?></td>
            <td><?php echo htmlspecialchars($u['nombre_real']); ?></td>
            <td>
                <?php if(tienePermiso($conn, $user_id, 11)): ?>
                    <button class="btn-change" onclick="cargarVista('usuarios_actualizar.php?id=<?php echo $u['id_usuario']; ?>')">‚úèÔ∏è Editar</button>
                <?php endif; ?>

                <?php if(tienePermiso($conn, $user_id, 12)): ?>
                    
                    <?php if($u['estado'] == 0): // Corregido a doble igual ?>
                        <button class="btn-change" style="background-color: #48bb78;" onclick="ActivarFila(<?php echo $u['id_usuario']; ?>, 'usuario')"> &#128260 ACTIVAR</button>
                    <?php else: ?>
                        <?php if($u['id_usuario'] != 1): ?>
                           <button class="btn-danger" onclick="eliminarFila(<?php echo $u['id_usuario']; ?>, 'usuario')">üóëÔ∏è  ELIMINAR</button>
                        <?php endif; ?>
                    <?php endif; ?>

                <?php endif; ?>
            </td>
        </tr>
        <?php 
            endwhile; 
        endif;
        ?>
    </tbody>
</table>

<div id="paginacion" class="paginacion-container"></div>

<script>
    // Variables Globales
    let paginaActual = 1;
    let filasPorPagina = 10; // Valor por defecto

    // FunciÛn principal que se ejecuta al cargar
    document.addEventListener('DOMContentLoaded', function() {
        actualizarPaginacion();
    });

    // FunciÛn que se ejecuta cuando cambias el select
    function cambiarCantidad() {
        const selector = document.getElementById('selectorCantidad');
        const valor = parseInt(selector.value);
        
        filasPorPagina = valor;
        paginaActual = 1; // Volver siempre a la p·gina 1 al cambiar el filtro
        actualizarPaginacion();
    }

    // LÛgica de PaginaciÛn
    function actualizarPaginacion() {
        const tabla = document.getElementById('tablaUsuarios');
        const cuerpo = tabla.querySelector('tbody');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const contenedorBotones = document.getElementById('paginacion');
        const info = document.getElementById('infoRegistros');
        const totalFilas = filas.length;

        // Si se eligiÛ "Todos" (-1)
        let limite = (filasPorPagina === -1) ? totalFilas : filasPorPagina;
        let totalPaginas = Math.ceil(totalFilas / limite);

        // 1. Mostrar/Ocultar filas
        filas.forEach((fila, index) => {
            fila.style.display = 'none'; // Ocultar todo primero
            
            // Calcular rango visible
            let inicio = (paginaActual - 1) * limite;
            let fin = inicio + limite;

            if (index >= inicio && index < fin) {
                fila.style.display = ''; // Mostrar si est· en rango
            }
        });

        // 2. Generar Botones
        contenedorBotones.innerHTML = '';
        
        // BotÛn "Anterior"
        if (totalPaginas > 1) {
            let btnPrev = document.createElement('button');
            btnPrev.innerText = '<<';
            btnPrev.className = 'paginacion-btn';
            btnPrev.onclick = () => { if(paginaActual > 1) { paginaActual--; actualizarPaginacion(); }};
            if(paginaActual === 1) btnPrev.disabled = true;
            contenedorBotones.appendChild(btnPrev);
        }

        // N˙meros
        for (let i = 1; i <= totalPaginas; i++) {
            let btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'paginacion-btn ' + (i === paginaActual ? 'active' : '');
            btn.onclick = () => {
                paginaActual = i;
                actualizarPaginacion();
            };
            contenedorBotones.appendChild(btn);
        }

        // BotÛn "Siguiente"
        if (totalPaginas > 1) {
            let btnNext = document.createElement('button');
            btnNext.innerText = '>>';
            btnNext.className = 'paginacion-btn';
            btnNext.onclick = () => { if(paginaActual < totalPaginas) { paginaActual++; actualizarPaginacion(); }};
            if(paginaActual === totalPaginas) btnNext.disabled = true;
            contenedorBotones.appendChild(btnNext);
        }

        // Texto informativo
    }
</script>
</div>