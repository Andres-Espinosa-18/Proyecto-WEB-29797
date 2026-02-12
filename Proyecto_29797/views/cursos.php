<?php
// 1. Incluimos DB y Sesión
require_once '../server/db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$user_id = $_SESSION['id_usuario'] ?? 0;


function tienePermisoCurso($conn, $user_id, $id_menu) {
    if ($user_id == 0) return false;
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
    $res = $conn->query($sql);
    return ($res->fetch_assoc()['total'] > 0);
}

// NOTA: Asume que el ID de menú para cursos es, por ejemplo, 20. 
// Ajusta los números (20, 21, 22) según tu tabla de menús.
?>

<div class="contenedor">
    <h2>Gestión de Cursos</h2>

	    <div style="display: flex; gap: 5px;">
        <input type="text" id="inputBusqueda" placeholder="Buscar por username..." 
               style="padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 6px; width: 250px; outline: none; transition: border 0.3s;"
               onfocus="this.style.borderColor='#3182ce'" onblur="this.style.borderColor='#cbd5e0'">
        
        <button onclick="ejecutarBusqueda('curso')" 
                style="background-color: #3182ce; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: background 0.3s;"
                onmouseover="this.style.backgroundColor='#2b6cb0'" onmouseout="this.style.backgroundColor='#3182ce'">
            Buscar
        </button>
        
        <button onclick="cargarVista('cursos.php')" 
                style="background-color: #edf2f7; color: #4a5568; border: 1px solid #cbd5e0; padding: 8px 12px; border-radius: 6px; cursor: pointer;"
                title="Limpiar b�squeda">
            Limpiar
        </button>
    </div>
	
	<div>
            <?php if(tienePermisoCurso($conn, $user_id, 17)): // Permiso Crear ?>
                <button onclick="cargarVista('cursos_crear.php')" class="btn-success">+ Nuevo Curso</button>
            <?php endif; ?>
        </div>
    <div class="tabla-controles">
        

        <div>
            <label style="color: #4a5568; font-weight: bold;">Mostrar: </label>
            <select id="selectorCantidadCursos" onchange="cambiarCantidadCursos()">
                <option value="5">5 registros</option>
                <option value="10" selected>10 registros</option>
                <option value="20">20 registros</option>
                <option value="-1">Todos</option>
            </select>
            <span id="infoRegistrosCursos" style="color: #718096; font-size: 0.9em; margin-left: 10px;"></span>
        </div>
    </div>

    <table class="tabla-gestion" id="tablaCursos">
        <thead>
            <tr>
                <th>Nombre del Curso</th>
                <th>Descripción</th>
                <th>Fecha Inicio</th>
                <th>Duración</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $res = $conn->query("SELECT * FROM cursos ORDER BY id_curso DESC");
            if ($res):
                while($c = $res->fetch_assoc()):
            ?>
            <tr>
                <td><strong><?php echo htmlspecialchars($c['nombre_curso']); ?></strong></td>
                <td><?php echo htmlspecialchars(substr($c['descripcion'], 0, 50)) . '...'; ?></td>
                <td><?php echo date('d/m/Y', strtotime($c['fecha_inicio'])); ?></td>
                <td><?php echo $c['duracion_horas']; ?> hrs</td>
                <td>
                    <?php if(tienePermisoCurso($conn, $user_id, 18)): ?>
                        <button class="btn-change" onclick="cargarVista('cursos_actualizar.php?id=<?php echo $c['id_curso']; ?>')">✏️ Editar</button>
                    <?php endif; ?>

                    <?php if(tienePermisoCurso($conn, $user_id, 19)): ?>
                        <?php if($c['estado'] == 0): ?>
                            <button class="btn-change" style="background-color: #38a169;" onclick="ActivarFila(<?php echo $c['id_curso']; ?>, 'curso')">🔄 ACTIVAR</button>
                        <?php else: ?>
                            <button class="btn-danger" onclick="eliminarFila(<?php echo $c['id_curso']; ?>, 'curso')">🗑️ ELIMINAR</button>
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

    <div id="paginacionCursos" class="paginacion-container"></div>
</div>

<script>
    // Variables ESPECÍFICAS para Cursos (evita conflicto con Usuarios)
    let paginaActualCursos = 1;
    let filasPorPaginaCursos = 10; 

    // Inicializar
    setTimeout(actualizarPaginacionCursos, 100);

    function cambiarCantidadCursos() {
        const selector = document.getElementById('selectorCantidadCursos');
        filasPorPaginaCursos = parseInt(selector.value);
        paginaActualCursos = 1; 
        actualizarPaginacionCursos();
    }

    function actualizarPaginacionCursos() {
        const tabla = document.getElementById('tablaCursos');
        if(!tabla) return; 

        const cuerpo = tabla.querySelector('tbody');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const contenedorBotones = document.getElementById('paginacionCursos');
        const info = document.getElementById('infoRegistrosCursos');
        const totalFilas = filas.length;

        let limite = (filasPorPaginaCursos === -1) ? totalFilas : filasPorPaginaCursos;
        let totalPaginas = Math.ceil(totalFilas / limite);

        // 1. Mostrar/Ocultar
        filas.forEach((fila, index) => {
            fila.style.display = 'none';
            let inicio = (paginaActualCursos - 1) * limite;
            let fin = inicio + limite;
            if (index >= inicio && index < fin) {
                fila.style.display = '';
            }
        });

        // 2. Botones
        contenedorBotones.innerHTML = '';
        
        // Prev
        if (totalPaginas > 1) {
            let btnPrev = document.createElement('button');
            btnPrev.innerText = '«';
            btnPrev.className = 'paginacion-btn';
            btnPrev.onclick = () => { if(paginaActualCursos > 1) { paginaActualCursos--; actualizarPaginacionCursos(); }};
            if(paginaActualCursos === 1) btnPrev.disabled = true;
            contenedorBotones.appendChild(btnPrev);
        }

        // Números
        for (let i = 1; i <= totalPaginas; i++) {
            let btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'paginacion-btn ' + (i === paginaActualCursos ? 'active' : '');
            btn.onclick = () => {
                paginaActualCursos = i;
                actualizarPaginacionCursos();
            };
            contenedorBotones.appendChild(btn);
        }

        // Next
        if (totalPaginas > 1) {
            let btnNext = document.createElement('button');
            btnNext.innerText = '»';
            btnNext.className = 'paginacion-btn';
            btnNext.onclick = () => { if(paginaActualCursos < totalPaginas) { paginaActualCursos++; actualizarPaginacionCursos(); }};
            if(paginaActualCursos === totalPaginas) btnNext.disabled = true;
            contenedorBotones.appendChild(btnNext);
        }

        
    }
</script>