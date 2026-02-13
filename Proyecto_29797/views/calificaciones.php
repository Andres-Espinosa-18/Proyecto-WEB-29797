<?php
// Iniciamos sesión para asegurar que tenemos el ID del estudiante
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>

<div class="contenedor">
    <div class="header-complex">
        <div class="header-left">
            <h2>Mis Calificaciones</h2>
            <p style="color: #7f8c8d; margin: 0;">Detalle de notas por cada curso inscrito.</p>
        </div>
        <div class="header-right no-print">
            <button class="btn-change" onclick="window.print()">&#128462; Imprimir Reporte</button>
        </div>
    </div>

    <div style="margin-top: 20px; overflow-x: auto;">
        <table class="tabla-gestion" id="tablaNotasEstudiante">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th style="text-align: center;">Nota 1</th>
                    <th style="text-align: center;">Nota 2</th>
                    <th style="text-align: center;">Nota 3</th>
                    <th style="text-align: center;">Recuperación</th>
                    <th style="text-align: center; background-color: #f0f4f8;">Promedio</th>
                    <th style="text-align: center;">Estado</th>
                </tr>
            </thead>
            <tbody id="tbody-notas">
                <tr><td colspan="7" style="text-align:center; padding:20px;">Cargando tus calificaciones...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function() {
    function cargarMisNotas() {
        fetch('server/obtener_notas_estudiante.php')
        .then(r => r.text())
        .then(html => {
            document.getElementById('tbody-notas').innerHTML = html;
        })
        .catch(err => console.error("Error al cargar notas: ", err));
    }
    cargarMisNotas();
})();
</script>