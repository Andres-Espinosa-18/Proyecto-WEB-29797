<?php
require_once '../server/db.php';
session_start();

// 1. Verificar sesión
$id = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : 0;
$rol = isset($_SESSION['rol_sistema']) ? $_SESSION['rol_sistema'] : '';

if ($id == 0) { echo "Sesión no válida."; exit; }

// 2. Variables iniciales
$nombre = "";
$usuario = "";
$correo = "";
$tipo = "";

// 3. Buscar datos según el rol (Automático)
if ($rol === 'estudiante') {
    // Busca en tabla ESTUDIANTES
    $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id_estudiante = ?"); // OJO: Si tu ID de sesión es el de usuario, ajusta esto.
    // Si id_usuario en sesión es diferente al id_estudiante, usa: "SELECT * FROM estudiantes WHERE usuario = (SELECT username FROM usuarios WHERE id_usuario=?)"
    // Asumiremos que guardas el id_usuario correcto en sesión.
    
    // Si tienes id_usuario en la tabla estudiantes, usa: WHERE id_usuario = ?
    $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id_usuario = ?"); // Ajuste común
    if(!$stmt) {
        // Si falla, intentamos por id_estudiante asumiendo que el ID de sesión es ese
        $stmt = $conn->prepare("SELECT * FROM estudiantes WHERE id_estudiante = ?");
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    
    if ($r) {
        $nombre = $r['nombre'] . " " . $r['apellido'];
        $usuario = $r['usuario'];
        $correo = $r['correo'];
        $tipo = "Estudiante";
    }
} else {
    // Busca en tabla USUARIOS (Admin/Docente)
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    
    if ($r) {
        $nombre = $r['username']; // O nombre_completo si tienes esa columna
        $usuario = $r['username'];
        $correo = isset($r['email']) ? $r['email'] : '';
        $tipo = "Administrativo";
    }
}
?>

<div class="header-complex">
    <div class="header-left">
        <h2 style="color: var(--primary);">Mi Perfil</h2>
        <p style="color: #666; margin:0;">Datos de cuenta (<?php echo $tipo; ?>)</p>
    </div>
</div>

<div style="padding: 20px;">
    <form id="formPerfil" onsubmit="event.preventDefault(); actualizarPerfil();">
        <input type="hidden" name="id_usuario" value="<?php echo $id; ?>">
        <input type="hidden" name="rol_actual" value="<?php echo $rol; ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px;">
            <div class="form-group">
                <label>Nombre:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($nombre); ?>" style="background:#f0f0f0;">
            </div>
            <div class="form-group">
                <label>Usuario:</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($usuario); ?>" readonly style="background:#f0f0f0;">
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 20px;">
            <label>Correo Electrónico:</label>
            <input type="email" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo); ?>" required>
        </div>

        <div style="border: 1px solid #ffeeba; background: #fff3cd; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
            <strong style="color:#856404;">Cambiar Contraseña</strong>
            <small style="display:block; color:#856404;">(Déjalo en blanco si no quieres cambiarla)</small>
            
            <div style="margin-top:10px; display:grid; grid-template-columns: 1fr 1fr; gap:15px;">
                <input type="password" name="clave1" id="p1" class="form-control" placeholder="Nueva Contraseña">
                <input type="password" name="clave2" id="p2" class="form-control" placeholder="Confirmar Contraseña">
            </div>
        </div>

        <div style="text-align: right;">
            <button type="button" class="btn btn-danger" onclick="cerrarModal()">Cerrar</button>
            <button type="submit" class="btn btn-success">&#128190; Actualizar</button>
        </div>
    </form>
</div>
