<?php
require_once '../server/db.php';
?>
<div class="contenedor">
    <h2>Bitácora de Auditoría del Sistema</h2>

    <div class="tabla-controles">
        
        <div>
            <label style="color: #4a5568; font-weight: bold;">Mostrar: </label>
            <select id="selectorCantidadAuditoria" onchange="cambiarCantidadAuditoria()">
                <option value="10">10 registros</option>
                <option value="25" selected>25 registros</option>
                <option value="50">50 registros</option>
                <option value="-1">Todos</option>
            </select>
            <span id="infoRegistrosAuditoria" style="color: #718096; font-size: 0.9em; margin-left: 10px;"></span>
        </div>

        <div></div>
    </div>

    <table class="tabla-gestion" id="tablaAuditoria">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Usuario</th>
                <th>Acción Realizada</th>
                <th>IP Origen</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // LIMIT alto para permitir paginación en el cliente
            $res = $conn->query("SELECT * FROM auditoria ORDER BY fecha_registro DESC LIMIT 500");
            while($a = $res->fetch_assoc()):
            ?>
            <tr>
                <td><small><?php echo $a['fecha_registro']; ?></small></td>
                <td><strong><?php echo htmlspecialchars($a['usuario_nombre']); ?></strong></td>
                <td><?php echo htmlspecialchars($a['accion']); ?></td>
                <td><code><?php echo $a['ip_conexion']; ?></code></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div id="paginacionAuditoria" class="paginacion-container"></div>
</div>

<script>
    // Variables específicas para Auditoría
    let paginaActualAuditoria = 1;
    let filasPorPaginaAuditoria = 25; // Por defecto 25

    // Inicializar al cargar
    setTimeout(actualizarPaginacionAuditoria, 100); 

    function cambiarCantidadAuditoria() {
        const selector = document.getElementById('selectorCantidadAuditoria');
        filasPorPaginaAuditoria = parseInt(selector.value);
        paginaActualAuditoria = 1; 
        actualizarPaginacionAuditoria();
    }

    function actualizarPaginacionAuditoria() {
        const tabla = document.getElementById('tablaAuditoria');
        if(!tabla) return; 

        const cuerpo = tabla.querySelector('tbody');
        const filas = Array.from(cuerpo.querySelectorAll('tr'));
        const contenedorBotones = document.getElementById('paginacionAuditoria');
        const info = document.getElementById('infoRegistrosAuditoria');
        const totalFilas = filas.length;

        let limite = (filasPorPaginaAuditoria === -1) ? totalFilas : filasPorPaginaAuditoria;
        let totalPaginas = Math.ceil(totalFilas / limite);

        // 1. Mostrar/Ocultar filas
        filas.forEach((fila, index) => {
            fila.style.display = 'none';
            let inicio = (paginaActualAuditoria - 1) * limite;
            let fin = inicio + limite;
            if (index >= inicio && index < fin) {
                fila.style.display = '';
            }
        });

        // 2. Generar Botones
        contenedorBotones.innerHTML = '';
        
        // Botón Anterior
        if (totalPaginas > 1) {
            let btnPrev = document.createElement('button');
            btnPrev.innerText = '«';
            btnPrev.className = 'paginacion-btn';
            btnPrev.onclick = () => { if(paginaActualAuditoria > 1) { paginaActualAuditoria--; actualizarPaginacionAuditoria(); }};
            if(paginaActualAuditoria === 1) btnPrev.disabled = true;
            contenedorBotones.appendChild(btnPrev);
        }

        // Números
        // Lógica simple para limitar botones si hay muchas páginas
        for (let i = 1; i <= totalPaginas; i++) {
            // Ocultar botones intermedios si hay más de 10 páginas para no saturar
            if(totalPaginas > 10 && (i !== 1 && i !== totalPaginas && Math.abs(paginaActualAuditoria - i) > 2)) {
                continue; 
            }
            
            let btn = document.createElement('button');
            btn.innerText = i;
            btn.className = 'paginacion-btn ' + (i === paginaActualAuditoria ? 'active' : '');
            btn.onclick = () => {
                paginaActualAuditoria = i;
                actualizarPaginacionAuditoria();
            };
            contenedorBotones.appendChild(btn);
        }

        // Botón Siguiente
        if (totalPaginas > 1) {
            let btnNext = document.createElement('button');
            btnNext.innerText = '»';
            btnNext.className = 'paginacion-btn';
            btnNext.onclick = () => { if(paginaActualAuditoria < totalPaginas) { paginaActualAuditoria++; actualizarPaginacionAuditoria(); }};
            if(paginaActualAuditoria === totalPaginas) btnNext.disabled = true;
            contenedorBotones.appendChild(btnNext);
        }

     
    }
</script>