<div class="contenedor">
    <div class="header-complex">
        
        <div class="header-left">
            <h2>Registro de Auditor&iacute;a</h2>
            
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-bar-advanced no-print">
                    <input type="text" id="terminoAudit" placeholder="Buscar usuario..." 
                           onkeyup="if(event.key === 'Enter') buscarAuditoria(1)">
                    
                    <input type="date" id="fechaAudit" onchange="buscarAuditoria(1)" style="border-left:1px solid #ccc; padding: 5px;">
                    
                    <button class="btn-change" onclick="buscarAuditoria(1)">&#128269;</button>

                    <select id="limiteAudit" onchange="buscarAuditoria(1)" 
                            style="margin-left: 20px; border: 1px solid #ccc; border-radius: 4px; width:auto; font-weight:bold; cursor:pointer; padding: 5px;">
                        <option value="10" selected>Ver 10</option>
                        <option value="20">Ver 20</option>
                        <option value="50">Ver 50</option>
                        <option value="100">Ver 100</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="header-right no-print">
            <div class="btn-group-top">
                <button class="btn-change" onclick="window.print()">&#128462; PDF</button>
            </div>
        </div>
    </div>

    <table class="tabla-gestion" id="tablaAuditResultados">
        <thead>
            <tr>
                <th style="width:180px;">Fecha y Hora</th>
                <th>Usuario</th>
                <th>Acci&oacute;n Realizada</th>
                <th style="width:130px;">IP Conexi&oacute;n</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4" style="text-align:center;">Cargando registros...</td></tr>
        </tbody>
    </table>
</div>

<script>
// Cargar al inicio con un pequeño delay para SPA
setTimeout(function() {
    buscarAuditoria(1);
}, 100);

function buscarAuditoria(pagina) {
    if (!pagina) pagina = 1;

    const termino = document.getElementById('terminoAudit').value;
    const fecha = document.getElementById('fechaAudit').value;
    const limite = document.getElementById('limiteAudit').value;

    const d = new FormData();
    d.append('tipo', 'auditoria_avanzada');
    d.append('termino', termino);
    d.append('fecha', fecha);
    d.append('pagina', pagina);
    d.append('limite', limite);

    fetch('server/busqueda_general.php', { method: 'POST', body: d })
    .then(r => r.text())
    .then(html => {
        document.querySelector('#tablaAuditResultados tbody').innerHTML = html;
    })
    .catch(err => console.error("Error cargando auditoría: " + err));
}
</script>