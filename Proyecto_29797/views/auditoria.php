<?php
require_once '../server/db.php';
?>
<div class="contenedor">
    <h2>Bitácora de Auditoría del Sistema</h2>

    <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e2e8f0; display: flex; gap: 15px; align-items: flex-end;">
        <div style="flex: 1;">
            <label style="display:block; font-size: 0.85em; color: #4a5568; margin-bottom: 5px; font-weight: bold;">Filtrar por Usuario:</label>
            <input type="text" id="busqUser" placeholder="Escribe un nombre..." 
                   style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
        </div>
        <div style="flex: 1;">
            <label style="display:block; font-size: 0.85em; color: #4a5568; margin-bottom: 5px; font-weight: bold;">Filtrar por Fecha:</label>
            <input type="date" id="busqFecha" 
                   style="width: 100%; padding: 8px; border: 1px solid #cbd5e0; border-radius: 4px;">
        </div>
        <div>
            <button onclick="buscarAuditoriaEspecial()" 
                    style="background-color: #3182ce; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                🔍 Buscar
            </button>
            <button onclick="cargarVista('auditoria.php')" 
                    style="background-color: #a0aec0; color: white; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; margin-left: 5px;">
                🔄 Limpiar
            </button>
        </div>
    </div>

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
            // Carga inicial (últimos 500)
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
    let paginaActualAuditoria = 1;
    let filasPorPaginaAuditoria = 25;

    // Función de Búsqueda Especial vinculada a busqueda_general.php
    function buscarAuditoriaEspecial() {
        const user = document.getElementById('busqUser').value;
        const fecha = document.getElementById('busqFecha').value;
        const cuerpo = document.querySelector('#tablaAuditoria tbody');

        const d = new FormData();
        d.append('tipo', 'auditoria');
        d.append('termino', user); // busqueda_general usa 'termino' para el nombre
        d.append('fecha', fecha);

        fetch('server/busqueda_general.php', {
            method: 'POST',
            body: d
        })
        .then(r => r.text())
        .then(html => {
            if(html.trim() === "") {
                cuerpo.innerHTML = "<tr><td colspan='4' style='text-align:center; padding:20px;'>No se encontraron registros con esos filtros.</td></tr>";
            } else {
                cuerpo.innerHTML = html;
            }
            // Reiniciamos paginación para los nuevos resultados
            paginaActualAuditoria = 1;
            actualizarPaginacionAuditoria();
        })
        .catch(err => alert("Error al buscar en auditoría"));
    }

    // Tu lógica de paginación existente
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
        const totalFilas = filas.length;

        let limite = (filasPorPaginaAuditoria === -1) ? totalFilas : filasPorPaginaAuditoria;
        let totalPaginas = Math.ceil(totalFilas / limite);

        filas.forEach((fila, index) => {
            fila.style.display = 'none';
            let inicio = (paginaActualAuditoria - 1) * limite;
            let fin = inicio + limite;
            if (index >= inicio && index < fin) {
                fila.style.display = '';
            }
        });

        contenedorBotones.innerHTML = '';
        if (totalPaginas > 1) {
            // Botón Anterior
            let btnPrev = document.createElement('button');
            btnPrev.innerText = '«';
            btnPrev.className = 'paginacion-btn';
            btnPrev.onclick = () => { if(paginaActualAuditoria > 1) { paginaActualAuditoria--; actualizarPaginacionAuditoria(); }};
            if(paginaActualAuditoria === 1) btnPrev.disabled = true;
            contenedorBotones.appendChild(btnPrev);

            // Números
            for (let i = 1; i <= totalPaginas; i++) {
                if(totalPaginas > 10 && (i !== 1 && i !== totalPaginas && Math.abs(paginaActualAuditoria - i) > 2)) continue; 
                
                let btn = document.createElement('button');
                btn.innerText = i;
                btn.className = 'paginacion-btn ' + (i === paginaActualAuditoria ? 'active' : '');
                btn.onclick = () => { paginaActualAuditoria = i; actualizarPaginacionAuditoria(); };
                contenedorBotones.appendChild(btn);
            }

            // Botón Siguiente
            let btnNext = document.createElement('button');
            btnNext.innerText = '»';
            btnNext.className = 'paginacion-btn';
            btnNext.onclick = () => { if(paginaActualAuditoria < totalPaginas) { paginaActualAuditoria++; actualizarPaginacionAuditoria(); }};
            if(paginaActualAuditoria === totalPaginas) btnNext.disabled = true;
            contenedorBotones.appendChild(btnNext);
        }
    }
</script>