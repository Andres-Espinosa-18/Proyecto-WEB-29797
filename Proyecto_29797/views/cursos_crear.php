<div style="padding:10px;">
    <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Nuevo Curso</h3>
    <form id="form-crear-curso">
        <div class="form-group">
            <label>Nombre del Curso:</label>
            <input type="text" name="nombre_curso" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Descripción:</label>
            <textarea name="descripcion" class="form-control" rows="2"></textarea>
        </div>
        <div style="display:flex; gap:10px;">
            <div class="form-group" style="flex:1;">
                <label>Fecha Inicio:</label>
                <input type="date" name="fecha_inicio" class="form-control" min="2026-02-13" max="2026-12-13" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Duración (Horas):</label>
                <input type="number" name="duracion" class="form-control" required>
            </div>
        </div>
        <div style="text-align:right; margin-top:15px; border-top:1px solid #eee; padding-top:10px;">
            <button type="button" class="btn-danger" onclick="cerrarModal()">Cancelar</button>
            <button type="button" class="btn-success" id="btnGuardarCurso">Guardar</button>
        </div>
    </form>
</div>
<script>
(function() {
    document.getElementById('btnGuardarCurso').onclick = function() {
        const f = document.getElementById('form-crear-curso');
        if(!f.checkValidity()){ f.reportValidity(); return; }
        fetch('server/cursos_guardar.php', { method:'POST', body:new FormData(f) })
        .then(r=>r.text()).then(m=>{ alert(m); cerrarModal(); buscarCursos(1); });
    };
})();
</script>