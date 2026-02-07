<?php
require_once '../server/db.php';
?>
<div class="contenedor">
    <h2>Bitácora de Auditoría del Sistema</h2>
    <table class="tabla-gestion">
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
            $res = $conn->query("SELECT * FROM auditoria ORDER BY fecha_registro DESC LIMIT 100");
            while($a = $res->fetch_assoc()):
            ?>
            <tr>
                <td><small><?php echo $a['fecha_registro']; ?></small></td>
                <td><strong><?php echo $a['usuario_nombre']; ?></strong></td>
                <td><?php echo $a['accion']; ?></td>
                <td><code><?php echo $a['ip_conexion']; ?></code></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>