<div class="contenedor">
    <div class="header-complex">
        <div class="header-left">
            <h2>Gesti&oacute;n de Estudiantes</h2>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                
                <div class="search-bar-advanced no-print">
                    <select id="criterioEst">
                        <option value="nombre">Nombre/Apellido</option>
                        <option value="cedula">C&eacute;dula</option>
                    </select>
                    
                    <input type="text" id="terminoEst" placeholder="Buscar estudiante..." 
                           onkeyup="if(event.key === 'Enter') buscarEstudiantes()">
                    
                    <button onclick="buscarEstudiantes()">&#128269;</button>

                    <select id="limiteEst" onchange="buscarEstudiantes()" style="border-left: 1px solid var(--border);">
                        <option value="5">Ver 5</option>
                        <option value="10" selected>Ver 10</option>
                        <option value="20">Ver 20</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="header-right">
            <a href="server/reporte_estudiantes_pdf.php" target="_blank" class="btn btn-danger" style="margin-right: 10px;">
        &#128196; PDF Reporte
    </a>

    <button class="btn btn-success" onclick="abrirModal('estudiantes_crear.php')">+ Nuevo Estudiante</button>
        </div>
    </div>

    <table class="tabla-gestion" id="tablaEstudiantes">
        <thead>
            <tr>
                <th>Apellidos y Nombres</th>
                <th class="text-center">C&eacute;dula</th>
                <th>Usuario</th>
                <th>Correo</th>
                <th class="text-center">Estado</th>
                <th class="acciones-col" style="text-align: center !important;">Acciones</th>
            </tr>
        </thead>
        <tbody id="tbody-estudiantes">
            <tr><td colspan="6" class="text-center">Iniciando carga...</td></tr>
        </tbody>
    </table>

    <img src="x" onerror="buscarEstudiantes()" style="display:none;">
</div>