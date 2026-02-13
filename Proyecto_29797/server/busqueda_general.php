<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// 1. Recibir parámetros generales
$tipo = $_POST['tipo'] ?? '';
$termino = isset($_POST['termino']) ? $conn->real_escape_string($_POST['termino']) : '';
$criterio = $_POST['criterio'] ?? 'general';

// 2. Variables para Paginación (Se usan en Usuarios y Roles)
$pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
$limite = isset($_POST['limite']) ? (int)$_POST['limite'] : 10;
$inicio = ($pagina - 1) * $limite;

switch ($tipo) {

    // =================================================================
    // CASO 1: USUARIOS (Paginación + Filtros + Acciones)
    // =================================================================
    case 'usuario_avanzado':
        
        // --- A. Construir Filtros (WHERE) ---
        $where = "WHERE 1=1";
        
        // Filtro de Estado
        if (isset($_POST['estado'])) {
            if ($_POST['estado'] == 'activos') $where .= " AND u.estado = 1";
            if ($_POST['estado'] == 'inactivos') $where .= " AND u.estado = 0";
        }

        // Buscador
        if (!empty($termino)) {
            switch ($criterio) {
                case 'cedula': $where .= " AND u.cedula LIKE '%$termino%'"; break;
                case 'username': $where .= " AND u.username LIKE '%$termino%'"; break;
                case 'nombre': $where .= " AND (u.nombre LIKE '%$termino%' OR u.apellido LIKE '%$termino%')"; break;
                case 'email': $where .= " AND u.email LIKE '%$termino%'"; break;
                default: $where .= " AND (u.username LIKE '%$termino%' OR u.nombre LIKE '%$termino%' OR u.apellido LIKE '%$termino%' OR u.cedula LIKE '%$termino%')"; break;
            }
        }

        // --- B. Contar Total (Para Paginación) ---
        $sqlCount = "SELECT COUNT(*) as total FROM usuarios u $where";
        $resCount = $conn->query($sqlCount);
        $totalRows = ($resCount) ? $resCount->fetch_assoc()['total'] : 0;
        $totalPaginas = ceil($totalRows / $limite);

        // --- C. Consulta de Datos (LIMIT) ---
        $sql = "SELECT u.*, r.nombre_rol, r.id_rol FROM usuarios u 
                LEFT JOIN usuario_roles ur ON u.id_usuario = ur.id_usuario 
                LEFT JOIN roles r ON ur.id_rol = r.id_rol 
                $where 
                ORDER BY u.apellido ASC 
                LIMIT $inicio, $limite";
        
        $res = $conn->query($sql);

        // --- D. Generar Filas de Tabla ---
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $es_admin = ($row['id_rol'] == 1); // Asumimos ID 1 es Admin Principal
                $colorEst = ($row['estado'] == 1) ? '#27ae60' : '#c0392b'; // Verde o Rojo
                $txtEst = ($row['estado'] == 1) ? 'Activo' : 'Inactivo';

                echo "<tr>
                    <td style='text-align:left;'>".htmlspecialchars($row['apellido'] . ' ' . $row['nombre'])."</td>
                    <td>".htmlspecialchars($row['cedula'])."</td>
                    <td>".htmlspecialchars($row['username'])."</td>
                    <td>".htmlspecialchars($row['nombre_rol'])."</td>
                    <td style='text-align:center; color:$colorEst; font-weight:bold;'>$txtEst</td>
                    <td class='acciones-col no-print'>
                        <button class='btn-change' onclick=\"abrirModal('usuarios_actualizar.php?id=".$row['id_usuario']."')\">&#9998; Editar</button>";
                
                if($es_admin) {
                    // Admin protegido
                    echo " <button class='btn-disabled' onclick=\"alert('No puedes eliminar al Administrador Principal')\">&#10006; Eliminar</button>";
                } else {
                    if($row['estado'] == 1) {
                        // Si está Activo -> Botón Rojo (Inactivar/Eliminar)
                        echo " <button class='btn-danger' onclick=\"cambiarEstadoUsuario(".$row['id_usuario'].", 0)\">&#10006; Eliminar</button>";
                    } else {
                        // Si está Inactivo -> Botón Verde (Activar)
                        echo " <button class='btn-success' onclick=\"cambiarEstadoUsuario(".$row['id_usuario'].", 1)\">&#10004; Activar</button>";
                    }
                }
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center; padding:20px;'>No se encontraron usuarios.</td></tr>";
        }

        // --- E. Fila Extra de Paginación (Colspan 6) ---
        if ($totalPaginas > 1) {
            echo "<tr class='no-print' style='background-color:#f8f9fa;'><td colspan='6' style='text-align:center; padding:10px;'>";
            
            // Botón Anterior
            if ($pagina > 1) {
                echo "<button class='btn-change' onclick='buscarUsuarios(".($pagina-1).")' style='margin-right:5px;'>&laquo; Anterior</button>";
            }

            // Info de página
            echo "<span style='margin:0 10px; font-weight:bold; color:#555;'>Página $pagina de $totalPaginas</span>";

            // Botón Siguiente
            if ($pagina < $totalPaginas) {
                echo "<button class='btn-change' onclick='buscarUsuarios(".($pagina+1).")' style='margin-left:5px;'>Siguiente &raquo;</button>";
            }

            echo "</td></tr>";
        }
        break;


    // =================================================================
    // CASO 2: ROLES (Paginación + Protección de Sistema)
    // =================================================================
    case 'rol_avanzado':
        
        // --- A. Construir Filtros ---
        $where = "WHERE 1=1";
        if (!empty($termino)) {
            if ($criterio == 'nombre') $where .= " AND nombre_rol LIKE '%$termino%'";
            elseif ($criterio == 'descripcion') $where .= " AND descripcion LIKE '%$termino%'";
            else $where .= " AND (nombre_rol LIKE '%$termino%' OR descripcion LIKE '%$termino%')";
        }

        // --- B. Contar Total ---
        $sqlCount = "SELECT COUNT(*) as total FROM roles $where";
        $resCount = $conn->query($sqlCount);
        $totalRows = ($resCount) ? $resCount->fetch_assoc()['total'] : 0;
        $totalPaginas = ceil($totalRows / $limite);

        // --- C. Consulta Limitada ---
        $sql = "SELECT * FROM roles $where ORDER BY id_rol ASC LIMIT $inicio, $limite";
        $res = $conn->query($sql);

        // --- D. Generar Filas ---
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                // Roles protegidos: ID 0 (Sin Rol) y ID 1 (Admin)
                $es_protegido = ($row['id_rol'] <= 1); 

                echo "<tr>
                    <td style='text-align:center;'>{$row['id_rol']}</td>
                    <td style='text-align:left; font-weight:bold;'>".htmlspecialchars($row['nombre_rol'])."</td>
                    <td style='text-align:left;'>".htmlspecialchars($row['descripcion'])."</td>
                    <td class='acciones-col no-print'>
                        <button class='btn-change' onclick=\"abrirModal('roles_actualizar.php?id={$row['id_rol']}')\">&#9998;  Editar</button>";
                
                if ($es_protegido) {
                    // Botón Gris (Disabled)
                    echo " <button class='btn-disabled' onclick=\"alert('Este rol es del sistema y no se puede eliminar')\">&#10006; Eliminar</button>";
                } else {
                    // Botón Rojo (Eliminar)
                    echo " <button class='btn-danger' onclick=\"eliminarRol({$row['id_rol']})\">&#10006; Eliminar</button>";
                }
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center; padding:20px;'>No se encontraron roles.</td></tr>";
        }

        // --- E. Paginación Roles (Colspan 4) ---
        if ($totalPaginas > 1) {
            echo "<tr class='no-print' style='background-color:#f8f9fa;'><td colspan='4' style='text-align:center; padding:10px;'>";
            
            if ($pagina > 1) {
                echo "<button class='btn-change' onclick='buscarRoles(".($pagina-1).")' style='margin-right:5px;'>&laquo; Anterior</button>";
            }
            
            echo "<span style='margin:0 10px; font-weight:bold; color:#555;'>Página $pagina de $totalPaginas</span>";
            
            if ($pagina < $totalPaginas) {
                echo "<button class='btn-change' onclick='buscarRoles(".($pagina+1).")' style='margin-left:5px;'>Siguiente &raquo;</button>";
            }
            echo "</td></tr>";
        }
        break;


    // =================================================================
    // CASO 3: AUDITORÍA (Listado Simple)
    // =================================================================
    case 'auditoria':
        $where = "WHERE 1=1";
        $fecha = $_POST['fecha'] ?? '';
        
        if (!empty($termino)) $where .= " AND usuario_nombre LIKE '%$termino%'";
        if (!empty($fecha)) $where .= " AND DATE(fecha_registro) = '$fecha'";
        
        // Aquí limitamos fijo a 50 para no saturar, o podrías implementar paginación igual que arriba
        $res = $conn->query("SELECT * FROM auditoria $where ORDER BY fecha_registro DESC LIMIT 50");
        
        if ($res && $res->num_rows > 0) {
            while ($a = $res->fetch_assoc()) {
                echo "<tr>
                    <td>".$a['fecha_registro']."</td>
                    <td>".htmlspecialchars($a['usuario_nombre'])."</td>
                    <td>".htmlspecialchars($a['accion'])."</td>
                    <td>".$a['ip_conexion']."</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center;'>No hay registros recientes.</td></tr>";
        }
        break;
		
		// =================================================================
    // CASO 3: AUDITORÍA (CON PAGINACIÓN Y FILTRO DE FECHA)
    // =================================================================
    case 'auditoria_avanzada':
        $pagina = isset($_POST['pagina']) ? (int)$_POST['pagina'] : 1;
        $limite = isset($_POST['limite']) ? (int)$_POST['limite'] : 10;
        $inicio = ($pagina - 1) * $limite;
        $fecha = $_POST['fecha'] ?? '';

        $where = "WHERE 1=1";
        if (!empty($termino)) $where .= " AND usuario_nombre LIKE '%$termino%'";
        if (!empty($fecha)) $where .= " AND DATE(fecha_registro) = '$fecha'";

        // 1. Contar Total
        $sqlCount = "SELECT COUNT(*) as total FROM auditoria $where";
        $totalRows = $conn->query($sqlCount)->fetch_assoc()['total'];
        $totalPaginas = ceil($totalRows / $limite);

        // 2. Consulta con Límite
        $res = $conn->query("SELECT * FROM auditoria $where ORDER BY fecha_registro DESC LIMIT $inicio, $limite");
        
        if ($res && $res->num_rows > 0) {
            while ($a = $res->fetch_assoc()) {
                echo "<tr>
                    <td style='font-size:0.9rem; color:#555;'>".$a['fecha_registro']."</td>
                    <td style='font-weight:bold;'>".htmlspecialchars($a['usuario_nombre'])."</td>
                    <td style='text-align:left;'>".htmlspecialchars($a['accion'])."</td>
                    <td style='color:#7f8c8d;'>".$a['ip_conexion']."</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center; padding:20px;'>No se encontraron registros de auditoría.</td></tr>";
        }

        // 3. Paginación (Fila extra)
        if ($totalPaginas > 1) {
            echo "<tr class='no-print' style='background-color:#f8f9fa;'><td colspan='4' style='text-align:center; padding:10px;'>";
            if ($pagina > 1) echo "<button class='btn-change' onclick='buscarAuditoria(".($pagina-1).")' style='margin-right:5px;'>&laquo; Ant</button>";
            echo "<span style='margin:0 10px; font-weight:bold;'>Página $pagina de $totalPaginas</span>";
            if ($pagina < $totalPaginas) echo "<button class='btn-change' onclick='buscarAuditoria(".($pagina+1).")' style='margin-left:5px;'>Sig &raquo;</button>";
            echo "</td></tr>";
        }
        break;
		
		// =================================================================
    // CASO: CURSOS (CON PAGINACIÓN Y ACCIÓN ALUMNOS)
    // =================================================================
    case 'cursos_avanzado':
        $where = "WHERE 1=1";
        if (!empty($termino)) {
            $where .= ($criterio == 'nombre') ? " AND nombre_curso LIKE '%$termino%'" : " AND descripcion LIKE '%$termino%'";
        }

        $totalRows = $conn->query("SELECT COUNT(*) as total FROM cursos $where")->fetch_assoc()['total'];
        $totalPaginas = ceil($totalRows / $limite);

        $res = $conn->query("SELECT * FROM cursos $where ORDER BY nombre_curso ASC LIMIT $inicio, $limite");

        if ($res && $res->num_rows > 0) {
			
            while ($row = $res->fetch_assoc()) {
									// Dentro del while ($row = $res->fetch_assoc()) en busqueda_general.php

					$id = $row['id_curso'];
					$estado = $row['estado']; // 1 para activo, 0 para inactivo

					// Definimos qué botón mostrar según el estado
					if ($estado == 1) {
						$btnEstado = "<button class='btn btn-danger' onclick='eliminarCurso($id)'> &#10006; Eliminar</button>";
					} else {
						// Si está en 0, mostramos el botón Activar (puedes usar el color de btn-success o btn-change)
						$btnEstado = "<button class='btn btn-success' onclick='activarCurso($id)'>&#10004; Activar</button>";
					}

					echo "<tr>
							<td style='font-weight:bold'>" . htmlspecialchars($row['nombre_curso']) . "</td>
							<td>" . htmlspecialchars($row['descripcion']) . "</td>
							<td class='text-center'>" . date("Y-m-d", strtotime($row['fecha_inicio'])) . "</td>
							<td class='text-center'>" . $row['duracion_horas'] . " h</td>
							<td class='acciones-col'>
								<button class='btn btn-change' onclick='editarCurso($id)'> &#9998; Editar</button>
								$btnEstado
								<button class='btn btn-alumnos' onclick='cargarAlumnos($id)'>&#127891; Alumnos</button>
							</td>
						  </tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>No hay cursos registrados.</td></tr>";
        }
        // ... (Paginación igual que antes)
        break;

    default:
        echo "<tr><td colspan='10' style='text-align:center'>Error: Criterio de búsqueda no válido.</td></tr>";
        break;
}
?>