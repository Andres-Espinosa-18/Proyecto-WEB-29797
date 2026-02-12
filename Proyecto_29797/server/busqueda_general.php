<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$user_id = $_SESSION['id_usuario'] ?? 0;

// Función auxiliar para verificar permisos dentro de la búsqueda
function tienePermisoBusqueda($conn, $user_id, $id_menu) {
    if ($user_id == 0) return false;
    $sql = "SELECT COUNT(*) as total FROM permisos_rol pr
            JOIN usuario_roles ur ON pr.id_rol = ur.id_rol
            WHERE ur.id_usuario = $user_id AND pr.id_menu = $id_menu";
    $res = $conn->query($sql);
    $row = $res ? $res->fetch_assoc() : ['total' => 0];
    return $row['total'] > 0;
}

$tipo = $_POST['tipo'] ?? '';
$termino = isset($_POST['termino']) ? $conn->real_escape_string($_POST['termino']) : '';
$fecha = isset($_POST['fecha']) ? $conn->real_escape_string($_POST['fecha']) : '';

$html = "";

switch ($tipo) {
    case 'usuario':
        $res = $conn->query("SELECT * FROM usuarios WHERE username LIKE '%$termino%' OR nombre_real LIKE '%$termino%'");
        while ($u = $res->fetch_assoc()) {
            $html .= "<tr>
                <td>".htmlspecialchars($u['username'])."</td>
                <td>".htmlspecialchars($u['nombre_real'])."</td>
                <td>";
            if(tienePermisoBusqueda($conn, $user_id, 11)) 
                $html .= "<button class='btn-change' onclick=\"cargarVista('usuarios_actualizar.php?id=".$u['id_usuario']."')\">&#9999; Editar</button> ";
            if(tienePermisoBusqueda($conn, $user_id, 12)) {
                if($u['estado'] == 0) $html .= "<button class='btn-change' style='background-color:#48bb78;' onclick=\"ActivarFila(".$u['id_usuario'].", 'usuario')\">&#128260; ACTIVAR</button>";
                elseif($u['id_usuario'] != 1) $html .= "<button class='btn-danger' onclick=\"eliminarFila(".$u['id_usuario'].", 'usuario')\">&#128465; ELIMINAR</button>";
            }
            $html .= "</td></tr>";
        }
        break;

    case 'rol':
        $res = $conn->query("SELECT * FROM roles WHERE nombre_rol LIKE '%$termino%'");
        while ($r = $res->fetch_assoc()) {
            $html .= "<tr>
                <td>".$r['id_rol']."</td>
                <td>".htmlspecialchars($r['nombre_rol'])."</td>
				<td>".htmlspecialchars($r['descripcion'])."</td>
                <td>
                    <button class='btn-change' onclick=\"cargarVista('roles_actualizar.php?id=".$r['id_rol']."')\">🛡️ Editar</button>
                    <button class='btn-danger' onclick=\"eliminarFila(".$r['id_rol'].", 'rol')\">&#128465;</button>
                </td></tr>";
        }
        break;

    case 'curso':
        $res = $conn->query("SELECT * FROM cursos WHERE nombre_curso LIKE '%$termino%'");
        while ($c = $res->fetch_assoc()) {
            $html .= "<tr>
                <td>".htmlspecialchars($c['nombre_curso'])."</td>
                <td>".htmlspecialchars($c['descripcion'])."</td>
                <td>".$c['fecha_inicio']."</td>
                <td>".htmlspecialchars($c['duracion_horas'])."</td>
                <td>
                    <button class='btn-change' onclick=\"cargarVista('cursos_actualizar.php?id=".$c['id_curso']."')\">&#9999;</button>
                    <button class='btn-danger' onclick=\"eliminarFila(".$c['id_curso'].", 'curso')\">&#128465;</button>
                </td></tr>";
        }
        break;

    case 'auditoria':
        // Búsqueda especial: por usuario Y/O por fecha
        $where = "WHERE 1=1";
        if (!empty($termino)) $where .= " AND usuario_nombre LIKE '%$termino%'";
        if (!empty($fecha)) $where .= " AND DATE(fecha_registro) = '$fecha'";
        
        $res = $conn->query("SELECT * FROM auditoria $where ORDER BY fecha_registro DESC");
        while ($a = $res->fetch_assoc()) {
            $html .= "<tr>
                <td>".$a['fecha_registro']."</td>
                <td>".htmlspecialchars($a['usuario_nombre'])."</td>
                <td>".htmlspecialchars($a['accion'])."</td>
                <td>".$a['ip_conexion']."</td>
            </tr>";
        }
        break;
}

echo $html;