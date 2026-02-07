<?php
// views/roles_crear.php
require_once '../server/db.php';
?>
<div class="contenedor">
    <div style="border-bottom: 2px solid #34495e; margin-bottom: 20px;">
        <h2>Registrar Nuevo Rol de Sistema</h2>
    </div>

    <form id="form-crear-rol">
        <div style="margin-bottom: 15px;">
            <label style="display:block; font-weight:bold;">Nombre del Rol:</label>
            <input type="text" name="nombre_rol" placeholder="Ej: Supervisor, Vendedor..." 
                   style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; font-weight:bold;">Descripción de Funciones:</label>
            <textarea name="descripcion" placeholder="Explique brevemente qué puede hacer este rol..." 
                      style="width:100%; height:100px; padding:10px; border:1px solid #ccc; border-radius:4px;"></textarea>
        </div>

        <div style="margin-top: 20px;">
            <button type="button" onclick="guardarNuevoRol()" class="btn-success">
                Guardar Nuevo Rol
            </button>
            <button type="button" onclick="cargarVista('crear_rol.php')" 
                    style="background:#95a5a6; color:white; border:none; padding:10px 20px; border-radius:4px; cursor:pointer;">
                Cancelar
            </button>
        </div>
    </form>
</div>

<script>
window.guardarNuevoRol = function() {
    const formulario = document.getElementById('form-crear-rol');
    const datos = new FormData(formulario);

    if(datos.get('nombre_rol').trim() === "") {
        alert("Por favor, introduce un nombre para el rol.");
        return;
    }

    fetch('server/roles_guardar.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.text())
    .then(mensaje => {
        alert(mensaje);
        // Regresamos a la tabla de gestión de roles para ver el nuevo registro
        cargarVista('crear_rol.php');
    })
    .catch(err => {
        console.error("Error en la petición:", err);
        alert("No se pudo conectar con el servidor.");
    });
};
</script>